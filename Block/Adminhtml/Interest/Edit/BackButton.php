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

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * BackButton class
 *
 * @package Elogic\BackInStock\Block\Adminhtml\Interest\Edit
 */
class BackButton extends GenericButton implements ButtonProviderInterface
{

    /**
     * Get Button Data for Back Button
     *
     * @return array
     */
    public function getButtonData(): array
    {
        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $this->getBackUrl()),
            'class' => 'back',
            'sort_order' => 10
        ];
    }

    /**
     * Get URL for back (reset) button
     *
     * @return string
     */
    public function getBackUrl(): string
    {
        return $this->getUrl('*/*/');
    }
}
