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

use Elogic\BackInStock\Controller\Adminhtml\Interest;
use Elogic\BackInStock\Model\InterestFactory;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

/**
 * Edit class
 *
 * @package Elogic\BackInStock\Controller\Adminhtml\Interest
 */
class Edit extends Interest
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
     * Edit constructor.
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
     * Edit action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('interest_id');
        $model = $this->interestFactory->create();

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Product Interest no longer exists.'));
                /** @var Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('elogic_backinstock_interest', $model);

        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Interest') : __('New Interest'),
            $id ? __('Edit Interest') : __('New Interest')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Interests'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Interest %1', $model->getId()) : __('New Interest'));
        return $resultPage;
    }
}
