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
use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Elogic\BackInStock\Controller\Adminhtml\Interest;

/**
 * Delete class
 *
 * @package Elogic\BackInStock\Controller\Adminhtml\Interest
 */
class Delete extends Interest
{

    /**
     * Property for Page Factory
     *
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Property for Interest Factory
     *
     * @var InterestFactory
     */
    protected $interestFactory;

    /**
     * Delete constructor.
     *
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param InterestFactory $interestFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        InterestFactory $interestFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->interestFactory = $interestFactory;
        parent::__construct($context, $coreRegistry);
    }
    /**
     * Delete action
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('interest_id');
        if ($id) {
            try {
                $model = $this->interestFactory->create();
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('You deleted the Interest.'));

                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());

                return $resultRedirect->setPath('*/*/edit', ['interest_id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a Interest to delete.'));

        return $resultRedirect->setPath('*/*/');
    }
}
