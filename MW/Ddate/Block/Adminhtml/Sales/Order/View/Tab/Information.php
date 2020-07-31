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

namespace MW\Ddate\Block\Adminhtml\Sales\Order\View\Tab;

/**
 * Class Information
 * @package MW\Ddate\Block\Adminhtml\Sales\Order\View\Tab
 */
class Information extends \Magento\Backend\Block\Template implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \MW\Ddate\Model\DdateFactory
     */
    protected $ddateFactory;

    /**
     * @var \MW\Ddate\Model\DdateStoreFactory
     */
    protected $ddateStoreFactory;

    /**
     * @var \MW\Ddate\Helper\Config
     */
    protected $configHelper;

    /**
     * @param \Magento\Backend\Block\Template\Context   $context
     * @param \Magento\Framework\Registry               $registry
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \MW\Ddate\Model\DdateFactory $dtimeFactory
     * @param \MW\Ddate\Model\DdateStoreFactory $ddateStoreFactory
     * @param \MW\Ddate\Helper\Config $configHelper
     * @param array                                     $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \MW\Ddate\Model\DdateFactory $ddateFactory,
        \MW\Ddate\Model\DdateStoreFactory $ddateStoreFactory,
        \MW\Ddate\Helper\Config $configHelper,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_objectManager = $objectManager;
        $this->ddateFactory = $ddateFactory;
        $this->ddateStoreFactory = $ddateStoreFactory;
        $this->configHelper = $configHelper;
        parent::__construct($context, $data);
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Information');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Information');
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->_coreRegistry->registry('current_order');
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->getRequest()->getParam('order_id');
    }

    /**
     * @return mixed
     */
    public function getDelivery()
    {

        $order = $this->getOrder();

        $delivery = [];
        $delivery['canShip'] = $order->canShip();
        $delivery['config'] = $this->getConfig();
        $incrementId = $order->getIncrementId();
        $delivery['incrementId'] = $incrementId;
        $delivery['loadTimeUrl'] = $this->getUrl('mw_ddate/order/loadTime');
        $delivery['saveDeliveryUrl'] = $this->getUrl('mw_ddate/order/saveTime');
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

//            $mwDeliverydateSecuritycode = $order->getData('mw_deliverydate_securitycode');
//            if ($mwDeliverydateSecuritycode) {
//                $delivery['mw_deliverydate_securitycode'] = $mwDeliverydateSecuritycode;
//            } else {
//                $delivery['mw_deliverydate_securitycode'] = '';
//            }
            $delivery['mw_delivery_sercurecode'] = '';
        }
        return $delivery;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $config = [];

        $config['limitWeeks'] = $this->configHelper->getConfigWeeksLimit();
        $config['deliverSaturdays'] = $this->configHelper->getConfigDeliverSaturdays();
        $config['deliverSundays'] = $this->configHelper->getConfigDeliverSundays();
        $config['disableDate'] = $this->configHelper->getConfigSpecialDays();
        $config['isEnableComment'] = $this->configHelper->getConfigEnableComment();

        return $config;
    }

    /**
     * @return string
     */
    public function getLoadingPath ()
    {
        return $this->getViewFileUrl('images/loader-2.gif');
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        $orderId = $this->getOrderId();
        $comment = $this->_objectManager->create(\Magento\Sales\Model\Order::class)
            ->load($orderId)->getMwCustomercommentInfo();

        return $comment;
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
