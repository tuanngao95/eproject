<?php
namespace MW\Ddate\Block;

class DisplayTimezone extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $dateTime;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $timezoneInterface;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\Timezone
     */
    protected $time;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \MW\Ddate\Helper\Config
     */
    protected $configHelper;

    /**
     * @var MW\Ddate\Model\DtimeFactory
     */
    protected $dtimeFactory;

    public function __construct(
        \MW\Ddate\Model\DtimeFactory $dtimeFactory,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Framework\Stdlib\DateTime\Timezone $time,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezoneInterface,
        \MW\Ddate\Helper\Config $configHelper
    )
    {
        $this->dtimeFactory = $dtimeFactory;
        $this->dateTime = $dateTime;
        $this->time = $time;
        $this->storeManager = $storeManager;
        $this->timezoneInterface = $timezoneInterface;
        $this->configHelper = $configHelper;

        parent::__construct($context);
    }

    public function timezone()
    {
        $delayHour = (int) ($this->configHelper->getConfigDelay()%24);
        $currentDate = $this->timezoneInterface->date()->format('Y-m-d');

        return __($delayHour);
    }

    public function display()
    {
        $timeModel = $this->dtimeFactory->create();
        $timeCollection = $timeModel->getCollection()->addFieldToFilter('is_active', 1);
        foreach ($timeCollection as $timeItem) {
            $disableDate = $timeItem->getSpecialDay();
            $allSpecialStatus[] = $timeItem->getSpecialday();
        }
        return $allSpecialStatus;
    }

    public function getStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }
}