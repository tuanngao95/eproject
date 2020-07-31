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
namespace MW\Ddate\Controller\Adminhtml\Ddate;

use MW\Ddate\Controller\Adminhtml\AbstractController;

/**
 * Class Index
 * @package MW\Ddate\Controller\Adminhtml\Ddate
 */
class Index extends AbstractController
{
    /**
     * @return void
     */
    public function execute()
    {
        $this->_initAction()->_addBreadcrumb(__('Manage Delivery Schedule'), __('Manage Delivery Schedule'));
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Manage Delivery Schedule'));
        $this->_view->renderLayout();
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
