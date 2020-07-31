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
namespace MW\Ddate\Model;

/**
 * Class Ddate
 * @package MW\Ddate\Model
 */
class Ddate extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Model construct that should be used for object initialization
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('MW\Ddate\Model\ResourceModel\Ddate');
    }
}
