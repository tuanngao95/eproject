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

namespace MW\Ddate\Observer;

use Magento\Framework\Event\Observer;

/**
 * Class EmailOrderSetTemplateVarsBefore
 * @package MW\Ddate\Observer
 */
class EmailOrderSetTemplateVarsBefore implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

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
     * CartUpdateAfter constructor.
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \MW\Ddate\Model\DdateFactory $dtimeFactory
     * @param \MW\Ddate\Model\DdateStoreFactory $ddateStoreFactory
     * @param \MW\Ddate\Helper\Config $configHelper
     */
    public function __construct
    (
        \Magento\Checkout\Model\Session $checkoutSession,
        \MW\Ddate\Model\DdateFactory $ddateFactory,
        \MW\Ddate\Model\DdateStoreFactory $ddateStoreFactory,
        \MW\Ddate\Helper\Config $configHelper
    )
    {
        $this->checkoutSession = $checkoutSession;
        $this->ddateFactory = $ddateFactory;
        $this->ddateStoreFactory = $ddateStoreFactory;
        $this->configHelper = $configHelper;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        if(!$this->configHelper->getModuleEnable()){
            return $this;
        }

        $trans = $observer->getEvent()->getTransport();
        $order = $trans->getOrder();
        $incrementId = $order->getIncrementId();
        $deliveryInfo = [];

        $ddateStore = $this->ddateStoreFactory->create()->getCollection()
            ->addFieldToFilter('increment_id', $incrementId)
            ->getFirstItem();

        if ($ddateStore->getData()) {
            $ddate = $this->ddateFactory->create()->load($ddateStore->getDdateId());
            $deliveryInfo['mw_delivery_date'] = $ddate->getDdate();
            $deliveryInfo['mw_delivery_time'] = $ddate->getDtimetext();
        }

        $deliveryInfo = new \Magento\Framework\DataObject($deliveryInfo);
        $trans->setData('deliveryInfo', $deliveryInfo);

        return $this;
    }
}
