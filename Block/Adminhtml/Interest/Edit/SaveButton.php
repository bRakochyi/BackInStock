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
 * SaveButton class
 *
 * @package Elogic\BackInStock\Block\Adminhtml\Interest\Edit
 */
class SaveButton extends GenericButton implements ButtonProviderInterface
{

    /**
     * Get Button Data for Save And Continue Button
     *
     * @return array
     */
    public function getButtonData(): array
    {
        return [
            'label' => __('Save Interest'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
    }
}
