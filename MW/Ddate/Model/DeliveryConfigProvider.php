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

namespace MW\Ddate\Model;

/**
 * Class DeliveryConfigProvider
 * @package MW\Ddate\Model
 */
class DeliveryConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
{
    /**
     * @var \MW\Ddate\Helper\Config
     */
    protected $configHelper;

    /**
     * @var \MW\Ddate\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var DdateFactory
     */
    protected $ddateFactory;

    /**
     * @var DdateStoreFactory
     */
    protected $ddateStoreFactory;

    /**
     * @var DtimeFactory
     */
    protected $dtimeFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $dateTime;

    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    protected $assetRepo;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $timezone;

    /**
     * DeliveryConfigProvider constructor.
     * @param \MW\Ddate\Helper\Config $configHelper
     * @param \MW\Ddate\Helper\Data $dataHelper
     * @param DdateFactory $ddateFactory
     * @param DdateStoreFactory $ddateStoreFactory
     * @param DtimeFactory $dtimeFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     * @param \Magento\Framework\View\Asset\Repository $assetRepo
     */
    public function __construct(
        \MW\Ddate\Helper\Config $configHelper,
        \MW\Ddate\Helper\Data $dataHelper,
        \MW\Ddate\Model\DdateFactory $ddateFactory,
        \MW\Ddate\Model\DdateStoreFactory $ddateStoreFactory,
        \MW\Ddate\Model\DtimeFactory $dtimeFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Magento\Framework\View\Asset\Repository $assetRepo
    ) {
        $this->configHelper = $configHelper;
        $this->dataHelper = $dataHelper;
        $this->ddateFactory = $ddateFactory;
        $this->ddateStoreFactory = $ddateStoreFactory;
        $this->dtimeFactory = $dtimeFactory;
        $this->storeManager = $storeManager;
        $this->dateTime = $dateTime;
        $this->timezone = $timezone;
        $this->assetRepo = $assetRepo;
    }

    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig()
    {
        $config = [
            'deliveryDateModule' => [
                'enable' => $this->configHelper->getModuleEnable(),
                'limitWeeks' => $this->configHelper->getConfigWeeksLimit(),
                'dateFormat' => $this->getFormatDate(),
                'firstColumnHeader' => $this->configHelper->getConfigFirstColumnHeader(),
                'description' => $this->configHelper->getConfigDescription(),
                'calenderDisplay' => $this->configHelper->getConfigCalenderDisplay(),
                'enableComment' => $this->configHelper->getConfigEnableComment(),
                'disableDate' => $this->configHelper->getConfigSpecialDays(),
                'deliverSaturdays' => $this->configHelper->getConfigDeliverSaturdays(),
                'deliverSundays' => $this->configHelper->getConfigDeliverSundays(),
                'currentDeliveryDate' => $this->dataHelper->getCurrentDeliveryDate(),
                'currentDeliveryTime' => $this->dataHelper->getCurrentDeliveryTime(),
                'currentDeliveryComment' => $this->dataHelper->getCurrentDeliveryComment(),
                'delayDay' => round($this->configHelper->getConfigDelay()/24),
                'calenderTime' => count($this->getCalenderTime())?$this->getCalenderTime()['items']:[],
                'calenderWeekdays' => count($this->getCalenderTime())?$this->getCalenderTime()['weekdays']:[],
                'avaiableDays' => count($this->getCalenderTime())?$this->getCalenderTime()['avaiable']:[],
                'ddateImgDateUrl' => $this->assetRepo->getUrlWithParams('MW_Ddate::images/ddate_selected_date.png', []),
                'ddateImgTimeUrl' => $this->assetRepo->getUrlWithParams('MW_Ddate::images/ddate_selected_time.png', []),
                'isSercuryCode' => 0
            ]
        ];
        return $config;
    }

    public function getFormatDate ()
    {
        $format = $this->configHelper->getConfigFormatDate();
        if ($format == 'mdY') {
            return 'mm-dd-yyyy';
        } elseif ($format == 'dmY') {
            return 'dd-mm-yyyy';
        } else {
            return 'yyyy-mm-dd';
        }
    }

    /**
     * @return array
     */
    public function getCalenderTime ()
    {
        $time = array();
        $result = array();
        $avaiableArray = array();

        $currentStoreId = $this->storeManager->getStore()->getStoreId();
        $timeModel = $this->dtimeFactory->create();
        $timeCollection = $timeModel->getCollection()->addFieldToFilter('is_active', 1);
        $timeCollection->addFieldToFilter(
            ['store_ids', 'store_ids'],
            [
                ['finset' => $currentStoreId],
                ['eq' => 0]
            ]
        );
        $timeCollection->getBySortOrder();

        if (!$timeCollection->getSize()) {
            return $result;
        }

        $delayHour = (int) ($this->configHelper->getConfigDelay()%24);

        $limitWeeks = $this->configHelper->getConfigWeeksLimit();
        $delayDay = round( ($this->configHelper->getConfigDelay()/24) );
        $disableDates = explode(";", $this->configHelper->getConfigSpecialDays());
//        $currentDate = $this->dateTime->gmtDate('Y-m-d', '+'.$delayDay.' day');
//        $currentStoreItme = $this->dateTime->gmtDate('H:i');
        $currentDate = $this->timezone->date()->format('Y-m-d');
        $currentDate = date('Y-m-d', strtotime( '+'.$delayDay.' day' , strtotime( $currentDate ) ));
        $currentStoreItme = $this->timezone->date()->format('H:i');

        $weekdays = array(
            0   => 'sun',
            1   => 'mon',
            2   => 'tue',
            3   => 'wed',
            4   => 'thu',
            5   => 'fri',
            6   => 'sat'
        );

        foreach ($timeCollection as $timeItem) {
            $maxSlot = $timeItem->getMaximumBooking()?$timeItem->getMaximumBooking():$this->configHelper->getConfigDefaultMaxDeliveriesSlot();
            $data['dtime_id'] = $timeItem->getDtimeId();
            $data['dtime'] = $timeItem->getDtime();
            $countDay = 0;
            $dateWeek = [];
            $data['days'] = [];
            for ($i = strtotime($currentDate); $i <= (strtotime($currentDate) + ($limitWeeks*7-1)*86400); $i = $i + 86400) {
                $countDay++;
                $avaiable = 1;
                $date = date("Y-m-d", $i);
                $dateLabel = date("m/d", $i);
                $dayOfWeek = date("w", $i);
                $ddateSchedule = $this->ddateFactory->create()->getCollection()
                    ->addFieldToFilter('ddate', $date)
                    ->addFieldToFilter('dtime', $timeItem->getDtimeId())
                    ->getFirstItem();
                if ($avaiable && in_array($date, $disableDates)) {
                    $avaiable = 0;
                }

                if ($avaiable && !$timeItem->getData($weekdays[$dayOfWeek])) {
                    $avaiable = 0;
                }

                if ($avaiable && $ddateSchedule->getData() && $ddateSchedule->getOrdered() >= $maxSlot) {
                    $avaiable = 0;
                }

                $lastHour = explode("-", $timeItem->getInterval())[1];
                if ($avaiable && $countDay==1 &&  (strtotime(date($currentStoreItme.':00')) - $delayHour*3600) >= strtotime(date($lastHour.':00'))) {
                    $avaiable = 0;
                }
                if ($date == $timeItem->getSpecialDay() && $timeItem->getSpecialday() == 0) {
                    $avaiable = 0;
                }

                $dateWeek[] = [
                    'date' => $date,
                    'weekday' => ucwords($weekdays[$dayOfWeek]),
                    'dateLabel' => $dateLabel,
                    'dtimeLabel' => $timeItem->getDtime()
                ];

                $dataAvaiable = [
                    'dtime_id' => $timeItem->getDtimeId(),
                    'date' => $date,
                    'avaiable' => $avaiable
                ];

                if ($countDay%7 == 0) {
                    $data['days'][] = $dateWeek;
                    $dateWeek = [];
                }
                array_push($avaiableArray, $dataAvaiable);
            }
            array_push($time, $data);
        }
        $result['items'] = $time;
        $result['weekdays'] = $data['days'];
        $result['avaiable'] = $avaiableArray;
        return $result;
    }
}