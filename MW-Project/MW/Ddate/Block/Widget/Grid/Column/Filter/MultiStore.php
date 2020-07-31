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

namespace MW\Ddate\Block\Widget\Grid\Column\Filter;

/**
 * Class MultiStore
 * @package MW\Ddate\Block\Widget\Grid\Column\Filter
 */
class MultiStore extends \Magento\Backend\Block\Widget\Grid\Column\Filter\Store
{
    /**
     * Form condition from element's value
     *
     * @return array|null
     */
    public function getCondition()
    {
        $value = $this->getValue();
        if ($value === null || $value == self::ALL_STORE_VIEWS) {
            return null;
        }
        if ($value == '_deleted_') {
            return ['null' => true];
        } else {
            return ['finset' => $value];
        }
    }
}