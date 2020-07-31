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
 * Class LoadTime
 * @package MW\Ddate\Controller\Adminhtml\Order
 */
class LoadTime extends Action
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \MW\Ddate\Helper\Config
     */
    protected $configHelper;

    /**\
     * @var \MW\Ddate\Model\DdateFactory
     */
    protected $ddateFactory;

    /**
     * LoadTime constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \MW\Ddate\Helper\Config $configHelper
     * @param \MW\Ddate\Model\ $ddateFactory
     */
    public function __construct
    (
        \Magento\Framework\App\Action\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \MW\Ddate\Helper\Config $configHelper,
        \MW\Ddate\Model\DdateFactory $ddateFactory
    )
    {
        parent::__construct($context);
        $this->checkoutSession = $checkoutSession;
        $this->configHelper = $configHelper;
        $this->ddateFactory = $ddateFactory;
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
        $ddate = date($this->getRequest()->getParam('ddate'));
        $dayOfWeek = '';
        switch (date('w', strtotime($ddate))){
            case 0:
                $dayOfWeek = 'sun';
                break;
            case 1:
                $dayOfWeek = 'mon';
                break;
            case 2:
                $dayOfWeek = 'tue';
                break;
            case 3:
                $dayOfWeek = 'wed';
                break;
            case 4:
                $dayOfWeek = 'thu';
                break;
            case 5:
                $dayOfWeek = 'fri';
                break;
            case 6:
                $dayOfWeek = 'sat';
                break;
        }
        $currentStoreId = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore()->getStoreId();
        $timeModel = $this->_objectManager->create('MW\Ddate\Model\Dtime');
        $timeCollection = $timeModel->getCollection()->addFieldToFilter('is_active', 1);
        $timeCollection->addFieldToFilter(
            ['store_ids', 'store_ids'],
            [
                ['finset' => $currentStoreId],
                ['eq' => 0]
            ]
        );
        if ($dayOfWeek) {
            $timeCollection->addFieldToFilter($dayOfWeek, 1);
        }
        $timeCollection->getBySortOrder();
        $timeArray = [];
        foreach ($timeCollection as $time) {
            if ($time->getSpecialDay() && in_array($this->getRequest()->getParam('ddate'), explode(";", $time->getSpecialDay())) && !$time->getSpecialday()) {
                continue;
            }
            $ddateModel = $this->ddateFactory->create()->getCollection()
                ->addFieldToFilter('ddate', $this->getRequest()->getParam('ddate'))
                ->addFieldToFilter('dtime', $time->getDtimeId())
                ->getFirstItem();
            if ($ddateModel->getData() && $ddateModel->getOrdered()>=$time->getMaximumBooking()) {
                continue;
            }
            $dataTime['value'] = $time->getDtimeId();
            $dataTime['label'] = $time->getDtime();
            array_push($timeArray, $dataTime);
        }

        $jsonHelper = $this->_objectManager->create("Magento\Framework\Json\Helper\Data");
        $this->getResponse()->setBody($jsonHelper->jsonEncode($timeArray));
    }
}