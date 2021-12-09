<?php
/**
 * Elogic Api InterestRepositoryInterface
 *
 * @category Elogic
 * @Package Elogic/BackInStock
 * @copyright 2021 Elogic
 * @author Bogdan Rakochyi
 */
namespace Elogic\BackInStock\Api;

use Elogic\BackInStock\Api\Data\InterestInterface;
use Elogic\BackInStock\Api\Data\InterestSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * InterestRepositoryInterface interface
 *
 * @package Elogic\BackInStock\Api
 */
interface InterestRepositoryInterface
{

    /**
     * Save Interest
     *
     * @param InterestInterface $interest
     * @return InterestInterface
     * @throws LocalizedException
     */
    public function save(
        InterestInterface $interest
    ): InterestInterface;

    /**
     * Retrieve Interest
     *
     * @param string $interestId
     * @return InterestInterface
     * @throws LocalizedException
     */
    public function getById(string $interestId): InterestInterface;

    /**
     * Retrieve Interest matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return InterestSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(
        SearchCriteriaInterface $searchCriteria
    ): InterestSearchResultsInterface;

    /**
     * Delete Interest
     *
     * @param InterestInterface $interest
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(
        InterestInterface $interest
    ): bool;

    /**
     * Delete Interest in ID
     *
     * @param string $interestId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById(string $interestId): bool;
}
