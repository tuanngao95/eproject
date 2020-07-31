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

namespace MW\Ddate\Helper;
/**
 * Class Data
 * @package MW\Ddate\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var Config
     */
    protected $configHelper;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * Data constructor.
     * @param Config $configHelper
     * @param \Magento\Checkout\Model\Session $checkoutSession
     */
    public function __construct(
        \MW\Ddate\Helper\Config $configHelper,
        \Magento\Checkout\Model\Session $checkoutSession
    ) {
        $this->configHelper = $configHelper;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @return mixed
     */
    public function getCurrentDeliveryDate()
    {
        return $this->checkoutSession->getData('mw_delivery_date');
    }

    /**
     * @return mixed
     */
    public function getCurrentDeliveryTime()
    {
        return $this->checkoutSession->getData('mw_delivery_time');
    }

    /**
     * @return mixed
     */
    public function getCurrentDeliveryComment()
    {
        return $this->checkoutSession->getData('mw_delivery_comment');
    }
}