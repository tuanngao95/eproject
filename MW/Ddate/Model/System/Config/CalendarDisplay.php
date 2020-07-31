<?php
/**
 * Mage-World
 *
 *  @category    Mage-World
 *  @package     MW
 *  @author      Mage-world Developer
 *
 *  @copyright   Copyright (c) 2018 Mage-World (https://www.mage-world.com/)
 */
namespace MW\Ddate\Model\System\Config;

/**
 * Class CalendarDisplay
 * @package MW\Ddate\Model\System\Config
 */
class CalendarDisplay implements \Magento\Framework\Option\ArrayInterface
{
    const CALENDER = 0;
    const DT_PICKER = 1;

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return[
            ['value' => self::CALENDER, 'label' => __('Calender')],
            ['value' => self::DT_PICKER, 'label' => __('Datetime Picker')]
        ];
    }
}
