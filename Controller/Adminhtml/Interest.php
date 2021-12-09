<?php
/**
 * Elogic Controller Adminhtml
 *
 * @category Elogic
 * @Package Elogic/BackInStock
 * @copyright 2021 Elogic
 * @author Bogdan Rakochyi
 */
namespace Elogic\BackInStock\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Registry;

/**
 * Interest abstract class
 *
 * @package Elogic\BackInStock\Controller\Adminhtml
 */
abstract class Interest extends Action
{
    /**
     * Property for Registry
     *
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * Constant for admin resource
     *
     * @var string
     */
    const ADMIN_RESOURCE = 'Elogic_BackInStock::top_level';

    /**
     * Construct for abstract class Interest
     *
     * @param Context $context
     * @param Registry $coreRegistry
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * Init page
     *
     * @param Page $resultPage
     * @return Page
     */
    public function initPage(Page $resultPage): Page
    {
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)
            ->addBreadcrumb(__('Elogic'), __('Elogic'))
            ->addBreadcrumb(__('Interest'), __('Interest'));
        return $resultPage;
    }
}
