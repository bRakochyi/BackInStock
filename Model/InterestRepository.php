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
use Elogic\BackInStock\Api\Data\InterestSearchResultsInterface;
use Elogic\BackInStock\Model\ResourceModel\Interest\CollectionFactory as InterestCollectionFactory;
use Exception;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Elogic\BackInStock\Api\Data\InterestInterfaceFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Elogic\BackInStock\Api\InterestRepositoryInterface;
use Magento\Framework\Reflection\DataObjectProcessor;
use Elogic\BackInStock\Model\ResourceModel\Interest as ResourceInterest;
use Magento\Framework\Api\DataObjectHelper;
use Elogic\BackInStock\Api\Data\InterestSearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;

/**
 * InterestRepository class
 *
 * @package Elogic\BackInStock\Model
 */
class InterestRepository implements InterestRepositoryInterface
{
    /**
     * Property for ExtensibleDataObjectConverter
     *
     * @var ExtensibleDataObjectConverter
     */
    protected $extensibleDataObjectConverter;

    /**
     * Property for StoreManagerInterface
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Property for InterestSearchResultsInterfaceFactory
     *
     * @var InterestSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * Property for DataObjectProcessor
     *
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * Property for InterestCollectionFactory
     *
     * @var InterestCollectionFactory
     */
    protected $interestCollectionFactory;

    /**
     * Property for JoinProcessorInterface
     *
     * @var JoinProcessorInterface
     */
    protected $extensionAttributesJoinProcessor;

    /**
     * Property for InterestFactory
     *
     * @var InterestFactory
     */
    protected $interestFactory;

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
    protected $dataInterestFactory;

    /**
     * Property for CollectionProcessorInterface
     *
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * Property for ResourceInterest
     *
     * @var ResourceInterest
     */
    protected $resource;

    /**
     * InterestRepository Construct
     *
     * @param ResourceInterest $resource
     * @param InterestFactory $interestFactory
     * @param InterestInterfaceFactory $dataInterestFactory
     * @param InterestCollectionFactory $interestCollectionFactory
     * @param InterestSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceInterest $resource,
        InterestFactory $interestFactory,
        InterestInterfaceFactory $dataInterestFactory,
        InterestCollectionFactory $interestCollectionFactory,
        InterestSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->interestFactory = $interestFactory;
        $this->interestCollectionFactory = $interestCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataInterestFactory = $dataInterestFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * Save method
     *
     * @param InterestInterface $interest
     * @return InterestInterface
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     */
    public function save(InterestInterface $interest): InterestInterface
    {
        if (empty($interest->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $interest->setStoreId($storeId);
        }
        $interestData = $this->extensibleDataObjectConverter->toNestedArray(
            $interest,
            [],
            InterestInterface::class
        );
        $interestModel = $this->interestFactory->create()->setData($interestData);

        try {
            $this->resource->save($interestModel);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the interest: %1',
                $exception->getMessage()
            ));
        }
        return $interestModel->getDataModel();
    }

    /**
     * get by ID method
     *
     * @param string $interestId
     * @return InterestInterface
     * @throws NoSuchEntityException
     */
    public function getById($interestId): InterestInterface
    {
        $interest = $this->interestFactory->create();
        $this->resource->load($interest, $interestId);
        if (!$interest->getId()) {
            throw new NoSuchEntityException(__('Interest with id "%1" does not exist.', $interestId));
        }
        return $interest->getDataModel();
    }

    /**
     * get list method
     *
     * @param SearchCriteriaInterface $criteria
     * @return InterestSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria): InterestSearchResultsInterface
    {
        $collection = $this->interestCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            InterestInterface::class
        );

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }

        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * delete method
     *
     * @param InterestInterface $interest
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(
        InterestInterface $interest
    ): bool
    {
        try {
            $interestModel = $this->interestFactory->create();
            $this->resource->load($interestModel, $interest->getInterestId());
            $this->resource->delete($interestModel);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Interest: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * delete by id method
     *
     * @param string $interestId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($interestId): bool
    {
        return $this->delete($this->getById($interestId));
    }
}
