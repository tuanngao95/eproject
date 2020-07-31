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
 * Class CartUpdateAfter
 * @package MW\Ddate\Observer
 */
class CartUpdateAfter implements \Magento\Framework\Event\ObserverInterface
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
     * CartUpdateAfter constructor.
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \MW\Ddate\Helper\Config $configHelper
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \MW\Ddate\Helper\Config $configHelper
    ) {
        $this->checkoutSession = $checkoutSession;
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

        $cart = $observer->getEvent()->getCart();

        if ($cart->getSummaryQty() == 0) {
            $this->checkoutSession->setData('mw_delivery_date', null);
            $this->checkoutSession->setData('mw_delivery_time', null);
            $this->checkoutSession->setData('mw_delivery_comment', null);
        }

        return $this;
    }
}
