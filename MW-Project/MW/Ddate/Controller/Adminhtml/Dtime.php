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
namespace MW\Ddate\Controller\Adminhtml;

use Magento\Backend\App\Action;

/**
 * Class Dtime
 * @package MW\Ddate\Controller\Adminhtml
 */
abstract class Dtime extends Action
{
    /**
     * Init action
     *
     * @return $this
     */
    protected function _initAction()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('MW_Ddate::mw_ddate_items')
            ->_addBreadcrumb(__('Delivery Schedule'), __('Delivery Schedule'));
        return $this;
    }

    /**
     * Is access to section allowed
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('MW_Ddate::mw_ddate_items');
    }
}
