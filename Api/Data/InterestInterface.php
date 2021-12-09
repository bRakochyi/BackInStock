<?php
/**
 * Elogic Api Data InterestInterface
 *
 * @category Elogic
 * @Package Elogic/BackInStock
 * @copyright 2021 Elogic
 * @author Bogdan Rakochyi
 */
namespace Elogic\BackInStock\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * InterestInterface interface
 *
 * @package Elogic\BackInStock\Api\Data
 */
interface InterestInterface extends ExtensibleDataInterface
{
    /**
     * Constant for column's name "updated_at" in table elogic_backinstock_interest
     */
    const UPDATED_AT = 'updated_at';

    /**
     * Constant for column's name "product_id" in table elogic_backinstock_interest
     */
    const PRODUCT_ID = 'product_id';

    /**
     * Constant for column's name "email" in table elogic_backinstock_interest
     */
    const EMAIL = 'email';

    /**
     * Constant for column's name "lastname" in table elogic_backinstock_interest
     */
    const LASTNAME = 'lastname';

    /**
     * Constant for column's name "name" in table elogic_backinstock_interest
     */
    const NAME = 'name';

    /**
     * Constant for column's name "product_name" in table elogic_backinstock_interest
     */
    const PRODUCT_NAME = 'product_name';

    /**
     * Constant for column's name "interest_id" in table elogic_backinstock_interest
     */
    const INTEREST_ID = 'interest_id';

    /**
     * Constant for column's name "created_at" in table elogic_backinstock_interest
     */
    const CREATED_AT = 'created_at';

    /**
     * Constant for column's name "has_notified" in table elogic_backinstock_interest
     */
    const HAS_NOTIFIED = 'has_notified';

    /**
     * Constant for column's name "store_id" in table elogic_backinstock_interest
     */
    const STORE_ID = 'store_id';

    /**
     * Get interest_id
     *
     * @return string|null
     */
    public function getInterestId(): ?string;

    /**
     * Set interest_id
     *
     * @param string $interestId
     * @return InterestInterface
     */
    public function setInterestId(string $interestId): InterestInterface;

    /**
     * Get product_id
     *
     * @return string|null
     */
    public function getProductId(): ?string;

    /**
     * Set product_id
     *
     * @param string $productId
     * @return InterestInterface
     */
    public function setProductId(string $productId): InterestInterface;

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Elogic\BackInStock\Api\Data\InterestExtensionInterface|null
     */
    public function getExtensionAttributes(): ?\Elogic\BackInStock\Api\Data\InterestExtensionInterface;

    /**
     * Set an extension attributes object.
     *
     * @param \Elogic\BackInStock\Api\Data\InterestExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elogic\BackInStock\Api\Data\InterestExtensionInterface $extensionAttributes
    ): InterestInterface;

    /**
     * Get email
     *
     * @return string|null
     */
    public function getEmail(): ?string;

    /**
     * Set email
     *
     * @param string $email
     * @return InterestInterface
     */
    public function setEmail(string $email): InterestInterface;

    /**
     * Get name
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Set name
     *
     * @param string $name
     * @return InterestInterface
     */
    public function setName(string $name): InterestInterface;

    /**
     * Get product_name
     *
     * @return string|null
     */
    public function getProductName(): ?string;

    /**
     * Set product_name
     *
     * @param string $productName
     * @return InterestInterface
     */
    public function setProductName(string $productName): InterestInterface;

    /**
     * Get lastname
     *
     * @return string|null
     */
    public function getLastname(): ?string;

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return InterestInterface
     */
    public function setLastname(string $lastname): InterestInterface;

    /**
     * Get created_at
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * Set created_at
     *
     * @param string $createdAt
     * @return InterestInterface
     */
    public function setCreatedAt(string $createdAt): InterestInterface;

    /**
     * Get updated_at
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * Set updated_at
     *
     * @param string $updatedAt
     * @return InterestInterface
     */
    public function setUpdatedAt(string $updatedAt): InterestInterface;

    /**
     * Get has_notified
     *
     * @return string|null
     */
    public function getHasNotified(): ?string;

    /**
     * Set has_notified
     *
     * @param string $hasNotified
     * @return InterestInterface
     */
    public function setHasNotified(string $hasNotified): InterestInterface;

    /**
     * Get store_id
     *
     * @return string|null
     */
    public function getStoreId(): ?string;

    /**
     * Set store_id
     *
     * @param string $storeId
     * @return InterestInterface
     */
    public function setStoreId(string $storeId): InterestInterface;
}
