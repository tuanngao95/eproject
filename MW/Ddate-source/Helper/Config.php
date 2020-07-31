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
 * Class Config
 * @package MW\Ddate\Helper
 */
class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Config constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * @return mixed
     */
    public function getModuleEnable()
    {
        return $this->_scopeConfig->getValue('mw_ddate/group_general/active', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getConfigSpecialDays()
    {
        return $this->_scopeConfig->getValue('mw_ddate/group_general/special_days', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getConfigFormatDate()
    {
        return $this->_scopeConfig->getValue('mw_ddate/group_general/formatdate', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getConfigWeeksLimit()
    {
        return $this->_scopeConfig->getValue('mw_ddate/group_general/weeks', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getConfigDefaultMaxDeliveriesSlot()
    {
        return $this->_scopeConfig->getValue('mw_ddate/group_general/maximum_bookings', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getConfigReturnSlotShipped()
    {
        return $this->_scopeConfig->getValue('mw_ddate/group_general/return_slot_shipped', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getConfigReturnSlotCanceled()
    {
        return $this->_scopeConfig->getValue('mw_ddate/group_general/return_slot_cancel', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getConfigDelay()
    {
        return $this->_scopeConfig->getValue('mw_ddate/group_general/delay', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getConfigFirstColumnHeader()
    {
        return $this->_scopeConfig->getValue('mw_ddate/group_general/first_column_header', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getConfigDeliverSaturdays()
    {
        return $this->_scopeConfig->getValue('mw_ddate/group_general/deliver_saturdays', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getConfigDeliverSundays()
    {
        return $this->_scopeConfig->getValue('mw_ddate/group_general/deliver_sundays', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getConfigCalenderDisplay()
    {
        return $this->_scopeConfig->getValue('mw_ddate/group_general/calender_display', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getConfigUsingDayOff()
    {
        return $this->_scopeConfig->getValue('mw_ddate/group_general/dayoff', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getConfigEnableComment()
    {
        return $this->_scopeConfig->getValue('mw_ddate/group_general/comment', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getConfigIsDisableFirstSlot()
    {
        return $this->_scopeConfig->getValue('mw_ddate/group_general/disable_base_firststlot', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getConfigDescription()
    {
        return $this->_scopeConfig->getValue('mw_ddate/group_general/description', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function isOSCEnable()
    {
        return $this->_scopeConfig->getValue('mw_onestepcheckout/general/active', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}