<?php
/**
 * Elogic Block Adminhtml Interest Edit
 *
 * @category Elogic
 * @Package Elogic/BackInStock
 * @copyright 2021 Elogic
 * @author Bogdan Rakochyi
 */
namespace Elogic\BackInStock\Block\Adminhtml\Interest\Edit;

use Magento\Backend\Block\Widget\Context;

/**
 * GenericButton abstract class
 *
 * @package Elogic\BackInStock\Block\Adminhtml\Interest\Edit
 */
abstract class GenericButton
{
    /**
     * Property for Context
     *
     * @var Context
     */
    protected $context;

    /**
     * Construct for abstract class Generic Button
     *
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * Return model ID
     *
     * @return int|null
     */
    public function getModelId(): ?int
    {
        return $this->context->getRequest()->getParam('interest_id');
    }

    /**
     * Generate url by route and parameters
     *
     * @param string $route
     * @param array $params
     * @return  string
     */
    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
