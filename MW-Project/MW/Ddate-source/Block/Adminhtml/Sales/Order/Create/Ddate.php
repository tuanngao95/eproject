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

namespace MW\Ddate\Block\Adminhtml\Sales\Order\Create;

/**
 * Class Ddate
 * @package MW\Ddate\Block\Adminhtml\Sales\Order\Create
 */
class Ddate extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \MW\Ddate\Helper\Config
     */
    protected $configHelper;

    /**
     * Ddate constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \MW\Ddate\Helper\Config $configHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \MW\Ddate\Helper\Config $configHelper,
        array $data = []
    ) {
        $this->configHelper = $configHelper;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    public function getDelivery()
    {
        $delivery = [];
        $delivery['config'] = $this->getConfig();
        $delivery['loadTimeUrl'] = $this->getUrl('mw_ddate/order/loadTime');
        $delivery['saveDeliveryUrl'] = $this->getUrl('mw_ddate/order/saveTime');

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