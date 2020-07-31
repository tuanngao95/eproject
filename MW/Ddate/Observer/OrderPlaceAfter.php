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
 * Class OrderPlaceAfter
 * @package MW\Ddate\Observer
 */
class OrderPlaceAfter implements \Magento\Framework\Event\ObserverInterface
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
     * @var \Magento\Framework\App\State
     */
    protected $state;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * OrderPlaceAfter constructor.
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \MW\Ddate\Model\DdateFactory $ddateFactory
     * @param \MW\Ddate\Model\DtimeFactory $dtimeFactory
     * @param \MW\Ddate\Model\DdateStoreFactory $ddateStoreFactory
     * @param \MW\Ddate\Helper\Config $configHelper
     * @param \Magento\Framework\App\State $state
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \MW\Ddate\Model\DdateFactory $ddateFactory,
        \MW\Ddate\Model\DtimeFactory $dtimeFactory,
        \MW\Ddate\Model\DdateStoreFactory $ddateStoreFactory,
        \MW\Ddate\Helper\Config $configHelper,
        \Magento\Framework\App\State $state,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->ddateFactory = $ddateFactory;
        $this->dtimeFactory = $dtimeFactory;
        $this->ddateStoreFactory = $ddateStoreFactory;
        $this->configHelper = $configHelper;
        $this->state = $state;
        $this->request = $request;
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

        $order = $observer->getEvent()->getOrder();

        if ($order->getIsVirtual()) {
            return $this;
        }

        if ($this->state->getAreaCode() == \Magento\Framework\App\Area::AREA_ADMINHTML) {
            $this->saveDeliveryFromAdmin($order);

            return $this;
        }

        $deliveryDate = $this->checkoutSession->getData('mw_delivery_date', true);
        $deliveryTime = $this->checkoutSession->getData('mw_delivery_time', true);
        $deliveryComment = $this->checkoutSession->getData('mw_delivery_comment', true);

        $incrementId = $order->getIncrementId();

        if (!$deliveryDate) {
            $this->checkoutSession->setData('mw_delivery_date', null);
            $this->checkoutSession->setData('mw_delivery_time', null);
            throw new \Magento\Framework\Exception\LocalizedException(
                __('You haven\'t selected delivery date. Please select again.')
            );
            return;
        }

        if (!$deliveryTime) {
            $this->checkoutSession->setData('mw_delivery_date', null);
            $this->checkoutSession->setData('mw_delivery_time', null);
            throw new \Magento\Framework\Exception\LocalizedException(
                __('You haven\'t selected delivery time.')
            );
            return;
        } else {
            $dtimeModel = $this->dtimeFactory->create()->load($deliveryTime);
            if (!$dtimeModel->getData()) {
                $this->checkoutSession->setData('mw_delivery_date', null);
                $this->checkoutSession->setData('mw_delivery_time', null);
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Delivery time don\'t exist.')
                );
                return;
            }
            $dtimeSpecialDays = explode(";", $dtimeModel->getSpecialDay());
            $dtimeText = $dtimeModel->getDtime();
            $maxBooking = $dtimeModel->getMaximumBooking()?$dtimeModel->getMaximumBooking():$this->configHelper->getConfigDefaultMaxDeliveriesSlot();
        }

        $ddateModel = $this->ddateFactory->create()->getCollection()
            ->addFieldToFilter('ddate', $deliveryDate)
            ->addFieldToFilter('dtime', $deliveryTime)
            ->getFirstItem();

        if ($ddateModel->getData()) {
            if ($ddateModel->getOrdered() >= $maxBooking) {
                $this->checkoutSession->setData('mw_delivery_date', null);
                $this->checkoutSession->setData('mw_delivery_time', null);
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('This time have been full slot.')
                );
                return;
            }
            $ddateModel->setOrdered($ddateModel->getOrdered() + 1);
            $ddateModel->setTotalOrdered($ddateModel->getTotalOrdered() + 1);
        } else {
            $ddateModel = $this->ddateFactory->create();
            $ddateModel->setDdate($deliveryDate);
            $ddateModel->setDtime($deliveryTime);
            $ddateModel->setHoliday(0);
            if (in_array($deliveryDate, $dtimeSpecialDays)) {
                $ddateModel->setHoliday(1);
            }
            $ddateModel->setOrdered(1);
            $ddateModel->setTotalOrdered(1);
            $ddateModel->setDtimetext($dtimeText);
        }
        $ddateModel->save();

        $ddateStoreModel = $this->ddateStoreFactory->create();
        $ddateStoreModel->setIncrementId($incrementId);
        $ddateStoreModel->setDdateId($ddateModel->getDdateId());
        $ddateStoreModel->setDdateComment($deliveryComment);
        $ddateStoreModel->setDeliveryStatus(0);
        $ddateStoreModel->save();

        $this->checkoutSession->setData('mw_delivery_date', null);
        $this->checkoutSession->setData('mw_delivery_time', null);
        $this->checkoutSession->setData('mw_delivery_comment', null);

        return $this;
    }

    /**
     * @param $order
     */
    public function saveDeliveryFromAdmin($order)
    {
        $ddate = $this->request->getParam('delivery-date');
        $dtime = $this->request->getParam('delivery_time');
        $dcomment = $this->request->getParam('mw_delivery_comment');

        $incrementId = $order->getIncrementId();

        if ($ddate && !$dtime) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('You haven\'t selected delivery time.')
            );
            return;
        } else if ($ddate && $dtime) {
            $dtimeModel = $this->dtimeFactory->create()->load($dtime);
            if (!$dtimeModel->getData()) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Delivery time don\'t exist.')
                );
                return;
            }
            $dtimeSpecialDays = explode(";", $dtimeModel->getSpecialDay());
            $dtimeText = $dtimeModel->getDtime();
            $maxBooking = $dtimeModel->getMaximumBooking()?$dtimeModel->getMaximumBooking():$this->configHelper->getConfigDefaultMaxDeliveriesSlot();

            $ddateModel = $this->ddateFactory->create()->getCollection()
                ->addFieldToFilter('ddate', $ddate)
                ->addFieldToFilter('dtime', $dtime)
                ->getFirstItem();

            if ($ddateModel->getData()) {
                if ($ddateModel->getOrdered() >= $maxBooking) {
                    throw new \Magento\Framework\Exception\LocalizedException(
                        __('This time have been full slot.')
                    );
                    return;
                }
                $ddateModel->setOrdered($ddateModel->getOrdered() + 1);
                $ddateModel->setTotalOrdered($ddateModel->getTotalOrdered() + 1);
            } else {
                $ddateModel = $this->ddateFactory->create();
                $ddateModel->setDdate($ddate);
                $ddateModel->setDtime($dtime);
                $ddateModel->setHoliday(0);
                if (in_array($ddate, $dtimeSpecialDays)) {
                    $ddateModel->setHoliday(1);
                }
                $ddateModel->setOrdered(1);
                $ddateModel->setTotalOrdered(1);
                $ddateModel->setDtimetext($dtimeText);
            }
            $ddateModel->save();

            $ddateStoreModel = $this->ddateStoreFactory->create();
            $ddateStoreModel->setIncrementId($incrementId);
            $ddateStoreModel->setDdateId($ddateModel->getDdateId());
            $ddateStoreModel->setDdateComment($dcomment);
            $ddateStoreModel->setDeliveryStatus(0);
            $ddateStoreModel->save();
        }
    }
}
