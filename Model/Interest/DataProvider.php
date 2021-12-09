<?php
/**
 * Elogic Model Interest
 *
 * @category Elogic
 * @Package Elogic/BackInStock
 * @copyright 2021 Elogic
 * @author Bogdan Rakochyi
 */
namespace Elogic\BackInStock\Model\Interest;

use Elogic\BackInStock\Model\ResourceModel\Interest\Collection;
use Elogic\BackInStock\Model\ResourceModel\Interest\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * DataProvider class
 *
 * @package Elogic\BackInStock\Model\Interest
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * Property for loaded data
     */
    protected $loadedData;

    /**
     * Property for Collection
     *
     * @var Collection
     */
    protected $collection;

    /**
     * Property for DataPersistorInterface
     *
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * DataProvider Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $model) {
            $this->loadedData[$model->getId()] = $model->getData();
        }
        $data = $this->dataPersistor->get('elogic_backinstock_interest');

        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);
            $this->loadedData[$model->getId()] = $model->getData();
            $this->dataPersistor->clear('elogic_backinstock_interest');
        }

        return $this->loadedData;
    }
}
