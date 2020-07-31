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

namespace MW\Ddate\Controller\Adminhtml\Order;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\ResponseInterface;

/**
 * Class SaveTime
 * @package MW\Ddate\Controller\Adminhtml\Order
 */
class SaveTime extends Action
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
     * @var \MW\Ddate\Model\DtimeFactory
     */
    protected $dtimeModelFactory;

    /**
     * @var \MW\Ddate\Model\Ddate
     */
    protected $ddateFactory;

    /**
     * @var \MW\Ddate\Model\DdateStore
     */
    protected $ddateStoreFactory;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $orderFactory;

    /**
     * SelectTime constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \MW\Ddate\Helper\Config $configHelper
     * @param \MW\Ddate\Model\DdateFactory $ddateFactory
     * @param \MW\Ddate\Model\DdateStoreFactory $ddateStoreFactory
     * @param \MW\Ddate\Model\DtimeFactory $dtimeModelFactory
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \MW\Ddate\Helper\Config $configHelper,
        \MW\Ddate\Model\DdateFactory $ddateFactory,
        \MW\Ddate\Model\DdateStoreFactory $ddateStoreFactory,
        \MW\Ddate\Model\DtimeFactory $dtimeModelFactory,
        \Magento\Sales\Model\OrderFactory $orderFactory
    ) {
        parent::__construct($context);
        $this->checkoutSession = $checkoutSession;
        $this->configHelper = $configHelper;
        $this->ddateFactory = $ddateFactory;
        $this->ddateStoreFactory = $ddateStoreFactory;
        $this->dtimeModelFactory = $dtimeModelFactory;
        $this->orderFactory = $orderFactory;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $jsonHelper = $this->_objectManager->create("Magento\Framework\Json\Helper\Data");
        $result = [];

        $dtimeId = $this->getRequest()->getParam('dtime', null);
        $ddate = $this->getRequest()->getParam('ddate', null);
        $dcomment = $this->getRequest()->getParam('dcomment', null);
        $incrementId = $this->getRequest()->getParam('increment_id', null);

        if (!$incrementId) {
            $result['error'] = __("Order not found.");
            return $this->getResponse()->setBody($jsonHelper->jsonEncode($result));
        }

        if (!$dtimeId || !$ddate) {
            $result['error'] = __("Delivery information invalid.");
            return $this->getResponse()->setBody($jsonHelper->jsonEncode($result));
        }

        $ddateStore = $this->ddateStoreFactory->create()->getCollection()
            ->addFieldToFilter('increment_id', $incrementId)
            ->getFirstItem();
        $oldDdateStoreData = $ddateStore->getData();

        $oldDdate = $this->ddateFactory->create()->load($ddateStore->getDdateId());
        $oldDdateData = $oldDdate->getData();

        $ddateModel = $this->ddateFactory->create()->getCollection()
            ->addFieldToFilter('ddate', $ddate)
            ->addFieldToFilter('dtime', $dtimeId)
            ->getFirstItem();

        // save when not change data
        if (isset($oldDdateData['ddate_id']) && $oldDdateData['ddate_id'] == $ddateModel->getDdateId()) {
            $result['isNotChange'] = 1;
            $ddateStore->setDdateComment($dcomment)->save();
            return $this->getResponse()->setBody($jsonHelper->jsonEncode($result));
        }

        $dtimeModel = $this->dtimeModelFactory->create()->load($dtimeId);
        $dtimeSpecialDays = explode(";", $dtimeModel->getSpecialDay());
        $dtimeText = $dtimeModel->getDtime();
        $maxBooking = $dtimeModel->getMaximumBooking()?$dtimeModel->getMaximumBooking():$this->configHelper->getConfigDefaultMaxDeliveriesSlot();

        if ($ddateModel->getData()) {
            if ($ddateModel->getOrdered() >= $maxBooking) {
                $result['error'] = __("This Time have been full slot.");
                return $this->getResponse()->setBody($jsonHelper->jsonEncode($result));
            }

            $ddateModel->setOrdered($ddateModel->getOrdered() + 1);
            $ddateModel->setTotalOrdered($ddateModel->getTotalOrdered() + 1);
        } else {
            $ddateModel = $this->ddateFactory->create();
            $ddateModel->setDdate($ddate);
            $ddateModel->setDtime($dtimeId);
            $ddateModel->setHoliday(0);
            if (in_array($ddate, $dtimeSpecialDays)) {
                $ddateModel->setHoliday(1);
            }
            $ddateModel->setOrdered(1);
            $ddateModel->setTotalOrdered(1);
            $ddateModel->setDtimetext($dtimeText);
        }
        $ddateModel->save();

        $newDdateStore = $this->ddateStoreFactory->create();
        if ($oldDdateStoreData) {
            $newDdateStore->setData($oldDdateStoreData);
        } else {
            $newDdateStore->setIncrementId($incrementId);
            $newDdateStore->setDeliveryStatus(0);
        }
        $newDdateStore->setDdateId($ddateModel->getDdateId());
        $newDdateStore->setDdateComment($dcomment);
        $newDdateStore->save();

        // change old data
        if ($oldDdateStoreData) {
            $this->ddateStoreFactory->create()->load($oldDdateStoreData['ddate_id'])->delete();
        }

        if ($oldDdateData) {
            $this->ddateFactory->create()->load($oldDdateData['ddate_id'])
                ->setOrdered($oldDdate->getOrdered() - 1)
                ->setTotalOrdered($oldDdate->getTotalOrdered() - 1)
                ->save();
        }

        if ($this->configHelper->isOSCEnable()) {
            $order = $this->orderFactory->create()->loadByIncrementId($incrementId);
            $order->setData('mw_deliverydate_date', $ddateModel->getDdate());
            $order->setData('mw_deliverydate_time', $ddateModel->getDtimetext());
            $order->save();
        }

        $result['item'] = $newDdateStore->getData();
        $result['ddate'] = $ddateModel->getData();

        return $this->getResponse()->setBody($jsonHelper->jsonEncode($result));
    }
}