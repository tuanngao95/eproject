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
namespace MW\Ddate\Model\ResourceModel;

/**
 * Class DdateStore
 * @package MW\Ddate\Model\ResourceModel
 */
class DdateStore extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mwddate_store', 'ddate_store_id');
    }
}