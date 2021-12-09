<?php
/**
 * Elogic Model
 *
 * @category Elogic
 * @Package Elogic/BackInStock
 * @copyright 2021 Elogic
 * @author Bogdan Rakochyi
 */
namespace Elogic\BackInStock\Model;

use Elogic\BackInStock\Api\Data\InterestInterface;
use Elogic\BackInStock\Helper\Data;
use Elogic\BackInStock\Model\ResourceModel\Interest\Collection;
use Magento\Framework\Api\DataObjectHelper;
use Elogic\BackInStock\Api\Data\InterestInterfaceFactory;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * Interest class
 *
 * @package Elogic\BackInStock\Model
 */
class Interest extends AbstractModel
{
    /**
     * Property for DataObjectHelper
     *
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * Property for InterestInterfaceFactory
     *
     * @var InterestInterfaceFactory
     */
    protected $interestDataFactory;

    /**
     * Property for DateTime
     *
     * @var DateTime
     */
    protected $dateTime;

    /**
     * Property for Data
     *
     * @var Data
     */
    protected $helper;

    /**
     * Property for eventPrefix
     *
     * @var string
     */
    protected $_eventPrefix = 'elogic_backinstock_interest';

    /**
     * Interest Construct
     *
     * @param Context $context
     * @param Registry $registry
     * @param InterestInterfaceFactory $interestDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param ResourceModel\Interest $resource
     * @param Collection $resourceCollection
     * @param DateTime $dateTime
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        Context                  $context,
        Registry                 $registry,
        InterestInterfaceFactory $interestDataFactory,
        DataObjectHelper         $dataObjectHelper,
        ResourceModel\Interest   $resource,
        Collection               $resourceCollection,
        DateTime                 $dateTime,
        Data                     $helper,
        array                    $data = []
    ) {
        $this->interestDataFactory = $interestDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dateTime = $dateTime;
        $this->helper = $helper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Before save
     *
     * @return Interest
     */
    public function beforeSave()
    {
        $this->setUpdatedAt($this->dateTime->gmtDate());
        if ($this->isObjectNew()) {
            $this->setCreatedAt($this->dateTime->gmtDate());
        }

        $product = $this->helper->getProductById($this->getData('product_id'));
        if ($product && $product->getId()) {
            $this->setData('product_name', $product->getName());
        } else {
            $this->setData('product_name', 'Product not found');
        }

        return parent::beforeSave();
    }

    /**
     * Retrieve interest model with interest data
     *
     * @return InterestInterface
     */
    public function getDataModel(): InterestInterface
    {
        $interestData = $this->getData();

        $interestDataObject = $this->interestDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $interestDataObject,
            $interestData,
            InterestInterface::class
        );

        return $interestDataObject;
    }
}
