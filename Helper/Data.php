<?php
/**
 * Elogic Helper
 *
 * @category Elogic
 * @Package Elogic/BackInStock
 * @copyright 2021 Elogic
 * @author Bogdan Rakochyi
 */
namespace Elogic\BackInStock\Helper;

use Elogic\BackInStock\Model\InterestFactory;
use Exception;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\CatalogInventory\Api\StockStateInterface;
use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\DataObject;
use Magento\Framework\Escaper;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Data helper class
 *
 * @package Elogic\BackInStock\Helper
 */
class Data extends AbstractHelper
{
    /**
     * Constant for Customer has notified
     */
    const CUSTOMER_HAS_NOTIFIED = 1;

    /**
     * Constant for Customer not notified
     */
    const CUSTOMER_NOT_NOTIFIED = 0;

    /**
     * Constant for Customer fail notified
     */
    const CUSTOMER_FAIL_NOTIFIED = 2;

    /**
     * Constant for backinstock email template path
     */
    const XML_PATH_EMAIL_TEMPLATE = 'backinstock/email/template';

    /**
     * Constant for backinstock email identity path
     */
    const XML_PATH_EMAIL_IDENTITY = 'backinstock/email/email_identity';

    /**
     * Constant for transaction sender name path
     */
    const XML_PATH_EMAIL_SENDER_NAME = 'trans_email/ident_general/name';

    /**
     * Constant for transaction sender email path
     */
    const XML_PATH_EMAIL_SENDER_EMAIL = 'trans_email/ident_general/email';

    /**
     * Constant for backinstock module is enabled
     */
    const CONFIG_MODULE_IS_ENABLE = 'elogic_backinstock/general/enable';

    /**
     * Property for Logger Interface
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Property for Product Repository Interface
     *
     * @var ProductRepositoryInterface
     */
    protected $productRepositoryInterface;

    /**
     * Property for Interest Factory
     *
     * @var InterestFactory
     */
    protected $interestFactory;

    /**
     * Property for StockStateInterface
     *
     * @var StockStateInterface
     */
    protected $stockItem;

    /**
     * Property for TransportBuilder
     *
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * Property for StateInterface
     *
     * @var StateInterface
     */
    protected $inlineTranslation;

    /**
     * Property for ScopeConfigInterface
     *
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Property for StoreManagerInterface
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Property for Escaper
     *
     * @var Escaper
     */
    protected $escaper;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param LoggerInterface $logger
     * @param ProductRepositoryInterface $productRepositoryInterface
     * @param InterestFactory $interestFactory
     * @param StockRegistryInterface $stockItem
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $inlineTranslation
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param Escaper $escaper
     */
    public function __construct(
        Context                    $context,
        LoggerInterface            $logger,
        ProductRepositoryInterface $productRepositoryInterface,
        InterestFactory            $interestFactory,
        StockRegistryInterface     $stockItem,
        TransportBuilder           $transportBuilder,
        StateInterface             $inlineTranslation,
        ScopeConfigInterface       $scopeConfig,
        StoreManagerInterface      $storeManager,
        Escaper                    $escaper
    ) {
        $this->logger = $logger;
        $this->productRepositoryInterface = $productRepositoryInterface;
        $this->interestFactory = $interestFactory;
        $this->stockItem = $stockItem;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->escaper = $escaper;
        parent::__construct($context);
    }

    /**
     * Get product by ID
     *
     * @param mixed $productId
     * @param int|null $storeId
     * @return Product
     */
    public function getProductById($productId, $editMode = false, $storeId = null, $forceReload = false)
    {
        try {
            return $this->productRepositoryInterface->getById($productId, $editMode, $storeId, $forceReload);
        } catch (Exception $e) {
            $this->logger->critical($e);
            return false;
        }
    }

    /**
     * Run notify interests process
     *
     * @return void
     */
    public function notifyInterests()
    {
        $collection = $this->interestFactory
            ->create()
            ->getCollection()
            ->addFieldToFilter('has_notified', ['neq' => self::CUSTOMER_HAS_NOTIFIED]);

        $collection->setPageSize(100);
        $pages = $collection->getLastPageNumber();
        $currentPage = 1;
        do {
            $collection->setCurPage($currentPage);
            $collection->load();
            foreach ($collection as $check) {
                $product = $this->getProductById($check->getProductId());
                $save = false;
                if ($product && $product->getId()) {
                    $stock = $this->stockItem->getStockItem($product->getId());
                    if ($stock && (int) $stock->getQty() > 0) {
                        try {
                            $this->sendTransactionalEmail([
                                'firstname' => $check->getName(),
                                'lastname' => $check->getLastname(),
                                'email' => $check->getEmail(),
                                'productname' => $product->getName(),
                                'productlink' => $product->getProductUrl(),
                                'store' => $check->getStoreId()
                            ]);
                            $check->setHasNotified(self::CUSTOMER_HAS_NOTIFIED);
                            $save = true;
                        } catch (Exception $e) {
                            $this->logger->critical($e);
                            $check->setHasNotified(self::CUSTOMER_FAIL_NOTIFIED);
                            $save = true;
                        }
                    }
                } else {
                    $check->setHasNotified(self::CUSTOMER_FAIL_NOTIFIED);
                    $save = true;
                }

                if ($save) {
                    try {
                        $check->save();
                    } catch (Exception $e) {
                        $this->logger->critical($e);
                    }
                }
            }
            $currentPage++;
            $collection->clear();
        } while ($currentPage <= $pages);
    }

    /**
     * Send transactional email
     *
     * @param array $vars
     * @return void
     */
    public function sendTransactionalEmail(array $vars = [])
    {
        $email = $vars['email'] ?? null;
        $storeId = $vars['store'] ?? 1;

        if (empty($vars) || !$email) {
            return;
        }

        $this->inlineTranslation->suspend();
        try {
            $postObject = new DataObject();
            $postObject->setData($vars);

            $storeScope = ScopeInterface::SCOPE_STORE;

            $this->transportBuilder->setTemplateIdentifier(
                $this->scopeConfig->getValue(
                    self::XML_PATH_EMAIL_TEMPLATE,
                    $storeScope
                )
            )->setTemplateOptions(
                [
                    'area' => Area::AREA_FRONTEND,
                    'store' => $storeId,
                ]
            )->setTemplateVars(
                [
                    'firstname' => $vars['firstname'] ?? null,
                    'lastname' => $vars['lastname'] ?? null,
                    'email' => $email,
                    'productname' => $vars['productname'] ?? null,
                    'productlink' => $vars['productlink'] ?? null
                ]
            )->setFrom(
                [
                    'email' => $this->scopeConfig->getValue(
                        self::XML_PATH_EMAIL_SENDER_EMAIL,
                        $storeScope
                    ),
                    'name' => $this->scopeConfig->getValue(
                        self::XML_PATH_EMAIL_SENDER_NAME,
                        $storeScope
                    ),
                ]
            )->addTo(
                $this->escaper->escapeHtml($vars['email']),
                $this->escaper->escapeHtml($vars['firstname'])
            );

            $transport = $this->transportBuilder->getTransport();
            $transport->sendMessage();

            $this->inlineTranslation->resume();

            return true;
        } catch (Exception $e) {
            $this->logger->critical($e);
        }
        return false;
    }

    /**
     * Method for Module "Elogic_BackInStock" is enabled
     *
     * @return mixed
     */
    public function isModuleEnable()
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $isEnable = $this->scopeConfig->getValue(self::CONFIG_MODULE_IS_ENABLE, $storeScope);
        return $isEnable;
    }
}
