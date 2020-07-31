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

namespace MW\Ddate\Block\Adminhtml\Sales\Invoice\View;

/**
 * Class Ddate
 * @package MW\Ddate\Block\Adminhtml\Sales\Invoice\View
 */
class Ddate extends \Magento\Sales\Block\Adminhtml\Order\AbstractOrder
{
    /**
     * @var \MW\Ddate\Helper\Config
     */
    protected $configHelper;

    /**
     * @var \MW\Ddate\Model\DdateFactory
     */
    protected $ddateFactory;

    /**
     * @var \MW\Ddate\Model\DdateStoreFactory
     */
    protected $ddateStoreFactory;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * Ddate constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Sales\Helper\Admin $adminHelper
     * @param \MW\Ddate\Helper\Config $configHelper
     * @param \MW\Ddate\Model\DdateFactory $ddateFactory
     * @param \MW\Ddate\Model\DdateStoreFactory $ddateStoreFactory
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Helper\Admin $adminHelper,
        \MW\Ddate\Helper\Config $configHelper,
        \MW\Ddate\Model\DdateFactory $ddateFactory,
        \MW\Ddate\Model\DdateStoreFactory $ddateStoreFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        array $data = []
    ) {
        $this->_adminHelper = $adminHelper;
        $this->_coreRegistry = $registry;
        $this->configHelper = $configHelper;
        $this->ddateFactory = $ddateFactory;
        $this->ddateStoreFactory = $ddateStoreFactory;
        $this->_objectManager = $objectManager;
        parent::__construct($context, $registry, $adminHelper, $data);
    }

    /**
     * Retrieve required options from parent
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return void
     */
    protected function _beforeToHtml()
    {
        if (!$this->getParentBlock()) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Please correct the parent block for this block.')
            );
        }
        $this->setOrder($this->getParentBlock()->getParentBlock()->getParentBlock()->getOrder());

        foreach ($this->getParentBlock()->getParentBlock()->getParentBlock()->getOrderInfoData() as $key => $value) {
            $this->setDataUsingMethod($key, $value);
        }

        parent::_beforeToHtml();
    }

    /**
     * @return mixed
     */
    public function getDelivery()
    {
        $order = $this->getOrder();

        $delivery = [];
        $incrementId = $order->getIncrementId();
        $ddateStore = $this->ddateStoreFactory->create()->getCollection()
            ->addFieldToFilter('increment_id', array('eq' => $incrementId))
            ->getFirstItem();

        if (!$ddateStore->getData()) {
            return $delivery;
        }

        $ddate = $this->ddateFactory->create()->load($ddateStore->getDdateId());

        if ($ddate->getData()) {
            $delivery['mw_delivery_date'] = $ddate->getDdate();
            $delivery['mw_delivery_comment'] = $ddateStore->getDdateComment();
            $mwDtime = $ddate->getDtimetext();
            if ($mwDtime) {
                $delivery['mw_delivery_time'] = $mwDtime;
                $delivery['mw_delivery_time_id'] = $ddate->getDtime();
            } else {
                $delivery['mw_delivery_time'] = '';
            }

            $delivery['mw_delivery_sercurecode'] = '';
        }
        return $delivery;
    }

    /**
     * @param null $orderId
     *
     * @return bool
     */
    public function getLastItem($orderId = null)
    {
        if (!$orderId) {
            $order_id = $this->getOrderId();
        } else {
            $order_id = $orderId;
        }

        $order = $this->_loadOrder($order_id);
        $itemCollection = $order->getItemsCollection();
        $lastItem = $itemCollection->setPageSize(1)->setCurPage($itemCollection->getLastPageNumber())->getLastItem();

        if ($lastItem->getParentItemId()) {
            $lastId = $lastItem->getParentItemId();
        } else {
            $lastId = $lastItem->getId();
        }
        if ($lastId != $this->getParentBlock()->getItem()->getId()) {
            return false;
        }

        return true;
    }

    /**
     * @param $order_id
     *
     * @return \Magento\Sales\Model\Order
     */
    protected function _loadOrder($order_id)
    {
        if ($order_id) {
            /** @var \Magento\Sales\Model\Order $order */
            $order = $this->_objectManager->create(\Magento\Sales\Model\Order::class);

            return $order->load($order_id);
        } elseif ($invoiceId = $this->getRequest()->getParam('invoice_id')) {
            /** @var \Magento\Sales\Model\Order\Invoice $invoice */
            $invoice = $this->_objectManager->create(\Magento\Sales\Model\Order\Invoice::class);

            return $invoice->load($invoiceId)->getOrder();
        } elseif ($shipmentId = $this->getRequest()->getParam('shipment_id')) {
            /** @var \Magento\Sales\Model\Order\Shipment $shipment */
            $shipment = $this->_objectManager->create(\Magento\Sales\Model\Order\Shipment::class);

            return $shipment->load($shipmentId)->getOrder();
        } else {
            /** @var \Magento\Sales\Model\Order\Creditmemo $creditmemo */
            $creditmemo = $this->_objectManager->create(\Magento\Sales\Model\Order\Creditmemo::class);

            return $creditmemo->load($this->getRequest()->getParam('creditmemo_id'))->getOrder();
        }
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->configHelper->getModuleEnable()) {
            return '';
        }
        return parent::_toHtml();
    }
}