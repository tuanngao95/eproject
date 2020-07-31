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
namespace MW\Ddate\Controller\Adminhtml\Dtime;

use MW\Ddate\Controller\Adminhtml\Dtime;

/**
 * Class Index
 * @package MW\Ddate\Controller\Adminhtml\Dtime
 */
class Index extends Dtime
{
    /**
     * @return void
     */
    public function execute()
    {
        $this->_initAction()->_addBreadcrumb(__('Manage Delivery Times'), __('Manage Delivery Times'));
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Manage Delivery Times'));
        $this->_view->renderLayout();
    }
}
