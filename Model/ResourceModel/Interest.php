<?php
/**
 * Elogic Model ResourceModel
 *
 * @category Elogic
 * @Package Elogic/BackInStock
 * @copyright 2021 Elogic
 * @author Bogdan Rakochyi
 */
namespace Elogic\BackInStock\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Interest class
 *
 * @package Elogic\BackInStock\Model\ResourceModel
 */
class Interest extends AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('elogic_backinstock_interest', 'interest_id');
    }
}
