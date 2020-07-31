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
namespace MW\Ddate\Model\ResourceModel\Ddate;

/**
 * Class Collection
 * @package MW\Ddate\Model\ResourceModel\Ddate
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('MW\Ddate\Model\Ddate', 'MW\Ddate\Model\ResourceModel\Ddate');
    }
}
