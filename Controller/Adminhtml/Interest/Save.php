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
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Save class
 *
 * @package Elogic\BackInStock\Controller\Adminhtml\Interest
 */
class Save extends Action
{
    /**
     * Property for DataPersistorInterface
     *
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * Property for Interest Factory
     *
     * @var InterestFactory
     */
    protected $interestFactory;

    /**
     * Save constructor.
     *
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param InterestFactory $interestFactory
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        InterestFactory $interestFactory
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->interestFactory = $interestFactory;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('interest_id');

            $model = $this->interestFactory
                ->create()
                ->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Interest no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Interest.'));
                $this->dataPersistor->clear('elogic_backinstock_interest');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['interest_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Interest.'));
            }

            $this->dataPersistor->set('elogic_backinstock_interest', $data);
            return $resultRedirect->setPath('*/*/edit', ['interest_id' => $id]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
