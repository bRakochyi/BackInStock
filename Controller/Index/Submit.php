<?php
/**
 * Elogic Controller Index
 *
 * @category Elogic
 * @Package Elogic/BackInStock
 * @copyright 2021 Elogic
 * @author Bogdan Rakochyi
 */
namespace Elogic\BackInStock\Controller\Index;

use Elogic\BackInStock\Api\Data\InterestInterfaceFactory;
use Elogic\BackInStock\Api\InterestRepositoryInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Json\Helper\Data;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Submit controller class
 *
 * @package Elogic\BackInStock\Controller\Index
 */
class Submit extends Action
{
    /**
     * Property for Page Factory
     *
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Property for Data
     *
     * @var Data
     */
    protected $jsonHelper;

    /**
     * Property for LoggerInterface
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Property for InterestInterfaceFactory
     *
     * @var InterestInterfaceFactory
     */
    protected $interestInterfaceFactory;

    /**
     * Property for InterestRepositoryInterface
     *
     * @var InterestRepositoryInterface
     */
    protected $interestRepositoryInterface;

    /**
     * Property for StoreManagerInterface
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Property for Validator
     *
     * @var Validator
     */
    protected $formKeyValidator;

    /**
     * Submit Constructor
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Data $jsonHelper
     * @param LoggerInterface $logger
     * @param InterestInterfaceFactory $interestInterfaceFactory
     * @param InterestRepositoryInterface $interestRepositoryInterface
     * @param StoreManagerInterface $storeManager
     * @param Validator $formKeyValidator
     */
    public function __construct(
        Context                     $context,
        PageFactory                 $resultPageFactory,
        Data                        $jsonHelper,
        LoggerInterface             $logger,
        InterestInterfaceFactory    $interestInterfaceFactory,
        InterestRepositoryInterface $interestRepositoryInterface,
        StoreManagerInterface       $storeManager,
        Validator                   $formKeyValidator
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        $this->logger = $logger;
        $this->interestInterfaceFactory = $interestInterfaceFactory;
        $this->interestRepositoryInterface = $interestRepositoryInterface;
        $this->storeManager = $storeManager;
        $this->formKeyValidator = $formKeyValidator;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $request = $this->getRequest();

        try {
            if (!$request->isPost() ||
                !$this->isAjax() ||
                !$this->formKeyValidator->validate($request)) {
                throw new LocalizedException(
                    __("There was a problem with your submission. Please try again.")
                );
            }

            $name = $request->getPostValue('name');
            $email = $request->getPostValue('email');
            $productId = $request->getPostValue('productId');

            $this->validateInput($name, $email, $productId);

            $parts = explode(" ", $name);
            $lastname = array_pop($parts);
            $firstname = implode(" ", $parts);

            $model = $this->interestInterfaceFactory
                ->create()
                ->setEmail($email)
                ->setName($firstname ?: $lastname)
                ->setLastname($lastname)
                ->setProductId($productId)
                ->setStoreId($this->getStoreId());

            $model = $this->interestRepositoryInterface->save($model);

            return $this->jsonResponse([
                'success' => true,
                'message' => '<strong>' . __("Thank you. We will contact you if the product comes back in stock.") . '</strong>'
            ]);
        } catch (LocalizedException $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => '<strong>' . __($e->getMessage()) . '</strong>'
            ]);
        } catch (\Exception $e) {
            $this->logger->critical($e);
            return $this->jsonResponse([
                'success' => false,
                'message' => '<strong>' . __($e->getMessage()) . '</strong>'
            ]);
        }
    }

    /**
     * Check Request is Ajax or not
     *
     * @return boolean
     */
    protected function isAjax()
    {
        $request = $this->getRequest();
        return $request->getServer('HTTP_X_REQUESTED_WITH') &&
            $request->getServer('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest';
    }

    /**
     * Create json response
     *
     * @return ResultInterface
     */
    public function jsonResponse($response = '')
    {
        return $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($response)
        );
    }

    /**
     * Validate input
     *
     * @param string $name
     * @param string $email
     * @param int $productId
     * @return void
     */
    public function validateInput($name = null, $email = null, $productId = null)
    {
        $error = false;

        if (!\Zend_Validate::is(trim($name), 'NotEmpty')) {
            $error = true;
        }
        if (!\Zend_Validate::is(trim($email), 'NotEmpty')) {
            $error = true;
        }
        if (!\Zend_Validate::is(trim($email), 'EmailAddress')) {
            $error = true;
        }
        if (!\Zend_Validate::is(trim($productId), 'NotEmpty')) {
            $error = true;
        }

        if (!$name || !$email || !$productId || $error) {
            throw new LocalizedException(__("Problem with submitted data"));
        }
    }

    /**
     * Get store identifier
     *
     * @return  int
     * @throws NoSuchEntityException
     */
    public function getStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }
}
