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
namespace MW\Ddate\Ui\Component\Sales\Order\Listing\Column;

/**
 * Class Dtime
 * @package MW\Ddate\Ui\Component\Sales\Order\Listing\Column
 */
class Dtime extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @var \MW\Ddate\Model\DdateFactory
     */
    protected $ddateFactory;

    /**
     * @var \MW\Ddate\Model\DdateStoreFactory
     */
    protected $ddateStoreFactory;

    /**
     * Ddate constructor.
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param \MW\Ddate\Model\DdateFactory $ddateFactory
     * @param \MW\Ddate\Model\DdateStoreFactory $ddateStoreFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \MW\Ddate\Model\DdateFactory $ddateFactory,
        \MW\Ddate\Model\DdateStoreFactory $ddateStoreFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->ddateFactory = $ddateFactory;
        $this->ddateStoreFactory = $ddateStoreFactory;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return void
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $incrementId = isset($item['increment_id'])?$item['increment_id']:'';
                if ($incrementId) {
                    $ddateStore = $this->ddateStoreFactory->create()->getCollection()
                        ->addFieldToFilter('increment_id', $incrementId)
                        ->getFirstItem();
                    if ($ddateStore->getData()) {
                        $ddate = $this->ddateFactory->create()->load($ddateStore->getDdateId());
                        $item[$this->getData('name')] = $ddate->getDtimetext();
                    } else {
                        $item[$this->getData('name')] = '';
                    }
                } else {
                    $item[$this->getData('name')] = $incrementId;
                }
            }
        }

        return $dataSource;
    }
}
