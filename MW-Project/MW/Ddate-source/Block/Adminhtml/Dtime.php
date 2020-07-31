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
namespace MW\Ddate\Block\Adminhtml;

/**
 * Class Dtime
 * @package MW\Ddate\Block\Adminhtml
 */
class Dtime extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_blockGroup = 'MW_Ddate';
        $this->_controller = 'adminhtml_dtime';
        $this->_headerText = __('Manage Delivery Times');
        $this->_addButtonLabel = __('Add New');
        parent::_construct();
    }
}
