<?php
/**
 * Mage-World
 *
 *  @category    Mage-World
 *  @package     MW
 *  @author      Mage-world Developer
 *
 *  @copyright   Copyright (c) 2018 Mage-World (https://www.mage-world.com/)
 */
namespace MW\Ddate\Ui\DataProvider\Form;

/**
 * Class Dtime
 * @package MW\Ddate\Ui\DataProvider\Form
 */
class Dtime extends \Magento\Ui\DataProvider\AbstractDataProvider
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
     * @var \MW\Ddate\Model\DtimeFactory
     */
    protected $dtimeFactory;

    /**
     * Dtime constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param \MW\Ddate\Model\DtimeFactory $dtimeFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \MW\Ddate\Model\DtimeFactory $dtimeFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->_request = $request;
        $this->dtimeFactory = $dtimeFactory;
        $this->collection = $this->dtimeFactory->create()->getCollection();
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
            $item = $this->dtimeFactory->create()->load($itemId);
            $this->loadedData[$item->getDtimeId()] = $item->getData();
        }
        return $this->loadedData;
    }
}