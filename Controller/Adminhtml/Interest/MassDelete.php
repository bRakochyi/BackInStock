<?php
/**
 * Elogic Controller Adminhtml Interest
 *
 * @category Elogic
 * @Package Elogic/BackInStock
 * @copyright 2021 Elogic
 * @author Bogdan Rakochyi
 */
namespace Elogic\BackInStock\Controller\Adminhtml\Interest;

use Elogic\BackInStock\Model\InterestFactory;
use Elogic\BackInStock\Model\ResourceModel\Interest\CollectionFactory;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;

/**
 * MassDelete Controller.
 *
 * @package Elogic\BackInStock\Controller\Adminhtml\Interest
 */
class MassDelete extends Action
{
    /**
     * Constant for Admin resource
     */
    const ADMIN_RESOURCE = 'Elogic_BackInStock::top_level';

    /**
     * Property for Filter
     *
     * @var Filter
     */
    protected $filter;

    /**
     * Property for Collection Factory
     *
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * Property for Interest Factory
     *
     * @var InterestFactory
     */
    protected $interestFactory;

    /**
     * MassDelete constructor
     *
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param InterestFactory $interestFactory
     */
    public function __construct(
        Context                                   $context,
        Filter                                    $filter,
        CollectionFactory                         $collectionFactory,
        InterestFactory $interestFactory
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->interestFactory = $interestFactory;
        parent::__construct($context);
    }

    /**
     * Execute action.
     *
     * @return Redirect
     * @throws Exception
     */
    public function execute(): Redirect
    {
        $ids = $this->getRequest()->getPost('selected');
        if ($ids) {
            $collection = $this->interestFactory->create()
                ->getCollection()
                ->addFieldToFilter('interest_id', ['in' => $ids]);
            $collectionSize = $collection->getSize();
            $deletedItems = 0;
            foreach ($collection as $item) {
                try {
                    $item->delete();
                    $deletedItems++;
                } catch (Exception $e) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                }
            }
            if ($deletedItems != 0) {
                if ($collectionSize != $deletedItems) {
                    $this->messageManager->addErrorMessage(
                        __('Failed to delete %1 interest item(s).', $collectionSize - $deletedItems)
                    );
                }
                $this->messageManager->addSuccessMessage(
                    __('A total of %1 interest item(s) have been deleted.', $deletedItems)
                );
            }
        }
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
