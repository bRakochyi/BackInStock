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

use Elogic\BackInStock\Model\Interest;
use Elogic\BackInStock\Model\InterestFactory;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * InlineEdit class
 *
 * @package Elogic\BackInStock\Controller\Adminhtml\Interest
 */
class InlineEdit extends Action
{
    /**
     * Property for Json Factory
     *
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * Property for Interest Factory
     *
     * @var InterestFactory
     */
    protected $interestFactory;

    /**
     * InlineEdit constructor.
     *
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param InterestFactory $interestFactory
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        InterestFactory $interestFactory
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->interestFactory = $interestFactory;
        parent::__construct($context);
    }

    /**
     * Inline edit action
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        /** @var Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $modelId) {
                    /** @var Interest $model */
                    $model = $this->interestFactory
                        ->create()
                        ->load($modelId);
                    try {
                        $model->setData($postItems[$modelId] + $model->getData());
                        $model->save();
                    } catch (Exception $e) {
                        $messages[] = "[Interest ID: {$modelId}]  {$e->getMessage()}";
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
}
