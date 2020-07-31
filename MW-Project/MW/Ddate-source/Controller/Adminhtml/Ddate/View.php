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

namespace MW\Ddate\Controller\Adminhtml\Ddate;

use Magento\Framework\App\ResponseInterface;

/**
 * Class View
 * @package MW\Ddate\Controller\Adminhtml\Ddate
 */
class View extends \MW\Ddate\Controller\Adminhtml\AbstractController
{

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
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
