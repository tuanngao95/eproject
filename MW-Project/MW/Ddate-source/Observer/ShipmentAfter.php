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
 * Class ShipmentAfter
 * @package MW\Ddate\Observer
 */
class ShipmentAfter implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \MW\Ddate\Model\Ddate
     */
    protected $ddateFactory;

    /**
     * @var \MW\Ddate\Model\Dtime
     */
    protected $dtimeFactory;

    /**
     * @var \MW\Ddate\Model\DdateStore
     */
    protected $ddateStoreFactory;

    /**
     * @var \MW\Ddate\Helper\Config
     */
    protected $configHelper;

    /**
     * OrderPlaceAfter constructor.
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \MW\Ddate\Model\DdateFactory $ddateFactory
     * @param \MW\Ddate\Model\DtimeFactory $dtimeFactory
     * @param \MW\Ddate\Model\DdateStoreFactory $ddateStoreFactory
     * @param \MW\Ddate\Helper\Config $configHelper
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \MW\Ddate\Model\DdateFactory $ddateFactory,
        \MW\Ddate\Model\DtimeFactory $dtimeFactory,
        \MW\Ddate\Model\DdateStoreFactory $ddateStoreFactory,
        \MW\Ddate\Helper\Config $configHelper
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->ddateFactory = $ddateFactory;
        $this->dtimeFactory = $dtimeFactory;
        $this->ddateStoreFactory = $ddateStoreFactory;
        $this->configHelper = $configHelper;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        if (!$this->configHelper->getModuleEnable()) {
            return $this;
        }

        $shipment = $observer->getEvent()->getShipment();
        $order = $shipment->getOrder();
        $incrementId = $order->getIncrementId();

        $ddateStoreModel = $this->ddateStoreFactory->create()->getCollection()
            ->addFieldToFilter('increment_id', array('eq' => $incrementId))
            ->getFirstItem();

        $isReturnSlot = $this->configHelper->getConfigReturnSlotShipped();

        if ($ddateStoreModel->getData() && !$order->canShip()) {
            $ddateStoreModel->setDeliveryStatus(\MW\Ddate\Model\Source\Ddate\DeliveryStatus::SHIPPED);

            if ($isReturnSlot) {
                $ddateModel = $this->ddateFactory->create()->load($ddateStoreModel->getDdateId());
                $ddateModel->setOrdered($ddateModel->getOrdered() - 1);
                $ddateModel->save();
            }

            $ddateStoreModel->save();
        }

        return $this;
    }
}
