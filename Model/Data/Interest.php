<?php
/**
 * Elogic Model Data
 *
 * @category Elogic
 * @Package Elogic/BackInStock
 * @copyright 2021 Elogic
 * @author Bogdan Rakochyi
 */
namespace Elogic\BackInStock\Model\Data;

use Elogic\BackInStock\Api\Data\InterestInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

/**
 * Interest class
 *
 * @package Elogic\BackInStock\Model\Data
 */
class Interest extends AbstractExtensibleObject implements InterestInterface
{

    /**
     * Get interest_id
     *
     * @return string|null
     */
    public function getInterestId(): ?string
    {
        return $this->_get(self::INTEREST_ID);
    }

    /**
     * Set interest_id
     *
     * @param string $interestId
     * @return InterestInterface
     */
    public function setInterestId(string $interestId): InterestInterface
    {
        return $this->setData(self::INTEREST_ID, $interestId);
    }

    /**
     * Get product_id
     *
     * @return string|null
     */
    public function getProductId(): ?string
    {
        return $this->_get(self::PRODUCT_ID);
    }

    /**
     * Set product_id
     *
     * @param string $productId
     * @return InterestInterface
     */
    public function setProductId(string $productId): InterestInterface
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Elogic\BackInStock\Api\Data\InterestExtensionInterface|null
     */
    public function getExtensionAttributes(): ?\Elogic\BackInStock\Api\Data\InterestExtensionInterface
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     *
     * @param \Elogic\BackInStock\Api\Data\InterestExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Elogic\BackInStock\Api\Data\InterestExtensionInterface $extensionAttributes
    ): Interest
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get email
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->_get(self::EMAIL);
    }

    /**
     * Set email
     *
     * @param string $email
     * @return InterestInterface
     */
    public function setEmail(string $email): InterestInterface
    {
        return $this->setData(self::EMAIL, $email);
    }

    /**
     * Get name
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->_get(self::NAME);
    }

    /**
     * Set name
     *
     * @param string $name
     * @return InterestInterface
     */
    public function setName(string $name): InterestInterface
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Get product_name
     *
     * @return string|null
     */
    public function getProductName(): ?string
    {
        return $this->_get(self::PRODUCT_NAME);
    }

    /**
     * Set product_name
     *
     * @param string $productName
     * @return InterestInterface
     */
    public function setProductName(string $productName): InterestInterface
    {
        return $this->setData(self::PRODUCT_NAME, $productName);
    }

    /**
     * Get lastname
     *
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->_get(self::LASTNAME);
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return InterestInterface
     */
    public function setLastname(string $lastname): InterestInterface
    {
        return $this->setData(self::LASTNAME, $lastname);
    }

    /**
     * Get created_at
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->_get(self::CREATED_AT);
    }

    /**
     * Set created_at
     *
     * @param string $createdAt
     * @return InterestInterface
     */
    public function setCreatedAt(string $createdAt): InterestInterface
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get updated_at
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->_get(self::UPDATED_AT);
    }

    /**
     * Set updated_at
     *
     * @param string $updatedAt
     * @return InterestInterface
     */
    public function setUpdatedAt(string $updatedAt): InterestInterface
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * Get has_notified
     *
     * @return string|null
     */
    public function getHasNotified(): ?string
    {
        return $this->_get(self::HAS_NOTIFIED);
    }

    /**
     * Set has_notified
     *
     * @param string $hasNotified
     * @return InterestInterface
     */
    public function setHasNotified(string $hasNotified): InterestInterface
    {
        return $this->setData(self::HAS_NOTIFIED, $hasNotified);
    }

    /**
     * Get store_id
     *
     * @return string|null
     */
    public function getStoreId(): ?string
    {
        return $this->_get(self::STORE_ID);
    }

    /**
     * Set store_id
     *
     * @param string $storeId
     * @return InterestInterface
     */
    public function setStoreId(string $storeId): InterestInterface
    {
        return $this->setData(self::STORE_ID, $storeId);
    }
}
