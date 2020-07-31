<?php
/**
 * Mage-World
 *
 * @category    Mage-World
 * @package     MW
 * @author      Mage-world Developer
 *
 * @copyright   Copyright (c) 2018 Mage-World (https://www.mage-world.com/)
 */
namespace MW\Ddate\Ui\DataProvider\View;

use Magento\Ui\DataProvider\Modifier\PoolInterface;

/**
 * Class Ddate
 * @package MW\Ddate\Ui\DataProvider\View
 */
class Ddate extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * Data Provider name
     *
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var \MW\Ddate\Model\DdateFactory
     */
    protected $ddateFactory;

    /**
     * @var PoolInterface
     */
    protected $pool;

    /**
     * Dtime constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param \MW\Ddate\Model\DdateFactory $ddateFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \MW\Ddate\Model\DdateFactory $ddateFactory,
        PoolInterface $pool,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->_request = $request;
        $this->ddateFactory = $ddateFactory;
        $this->pool = $pool;
        $this->collection = $this->ddateFactory->create()->getCollection();
        $this->dataPersistor = $dataPersistor;
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

        $itemId = $this->_request->getParam('id');
        if (!empty($itemId)) {
            $item = $this->ddateFactory->create()->load($itemId);
            $this->loadedData[$item->getDdateId()] = $item->getData();
        }
        return $this->loadedData;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getMeta()
    {
        $meta = parent::getMeta();

        /** @var ModifierInterface $modifier */
        foreach ($this->pool->getModifiersInstances() as $modifier) {
            $meta = $modifier->modifyMeta($meta);
        }

        return $meta;
    }
}