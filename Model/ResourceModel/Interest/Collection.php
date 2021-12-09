<?php
/**
 * Elogic Model ResourceModel Interest
 *
 * @category Elogic
 * @Package Elogic/BackInStock
 * @copyright 2021 Elogic
 * @author Bogdan Rakochyi
 */
namespace Elogic\BackInStock\Model\ResourceModel\Interest;

use Elogic\BackInStock\Model\Interest;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Collection class
 *
 * @package Elogic\BackInStock\Model\ResourceModel\Interest
 */
class Collection extends AbstractCollection
{
    /**
     * Property for id field
     *
     * @var string
     */
    protected $_idFieldName = 'interest_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            Interest::class,
            \Elogic\BackInStock\Model\ResourceModel\Interest::class
        );
    }
}
