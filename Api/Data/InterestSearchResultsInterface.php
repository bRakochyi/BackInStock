<?php
/**
 * Elogic Api Data InterestSearchResultsInterface
 *
 * @category Elogic
 * @Package Elogic/BackInStock
 * @copyright 2021 Elogic
 * @author Bogdan Rakochyi
 */
namespace Elogic\BackInStock\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * InterestSearchResultsInterface interface
 *
 * @package Elogic\BackInStock\Api\Data
 */
interface InterestSearchResultsInterface extends SearchResultsInterface
{

    /**
     * Get Interest list.
     *
     * @return InterestInterface[]
     */
    public function getItems(): array;

    /**
     * Set product_id list.
     *
     * @param InterestInterface[] $items
     * @return $this
     */
    public function setItems(array $items): InterestSearchResultsInterface;
}
