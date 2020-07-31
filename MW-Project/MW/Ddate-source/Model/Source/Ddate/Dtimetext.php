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

namespace MW\Ddate\Model\Source\Ddate;
/**
 * Class Dtimetext
 * @package MW\Ddate\Model\Source\Ddate
 */
class Dtimetext implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \MW\Ddate\Model\DtimeFactory
     */
    protected $dtimeFactory;

    /**
     * Dtimetext constructor.
     * @param \MW\Ddate\Model\DtimeFactory $dtimeFactory
     */
    public function __construct(
        \MW\Ddate\Model\DtimeFactory $dtimeFactory
    ) {
        $this->dtimeFactory = $dtimeFactory;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $options = array();
        foreach (self::getOptionHash() as $value => $label) {
            $options[] = array(
                'value'    => $value,
                'label'    => $label
            );
        }
        return $options;
    }

    /**
     * get available statuses.
     *
     * @return []
     */
    public function getOptionHash()
    {
        $dtimeCollection = $this->dtimeFactory->create()->getCollection();

        $option = [];

        foreach ($dtimeCollection as $dtime) {
            $option[$dtime->getDtimeId()] = $dtime->getDtime();
        }

        return $option;
    }
}