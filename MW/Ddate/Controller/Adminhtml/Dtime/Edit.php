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

use Magento\Framework\App\ResponseInterface;

/**
 * Class NewAction
 * @package MW\Ddate\Controller\Adminhtml\Dtime
 */
class Edit extends \MW\Ddate\Controller\Adminhtml\AbstractController
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
        $id = $this->getDtimeId();
        if ($id) {
            try {
                $model = $this->_objectManager->create('MW\Ddate\Model\Dtime')->load($id);
            } catch (\Magento\Framework\Exception\NoSuchEntityException $exception) {
                $this->messageManager->addError(__('This time no longer exists.'));
                $this->_redirect('*/*/*');
                return;
            }
        } else {
            /** @var \MW\EasyFaq\Model\Faq $model */
            $model = $this->_objectManager->create('MW\Ddate\Model\Dtime');
        }
        // set entered data if was error when we do save
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }

        $this->_initAction()->_addBreadcrumb(__('Manage Delivery Times'), __('Manage Delivery Times'));
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('New Delivery Times'));
        if ($id) {
            $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Edit Delivery Times'));
        }

        $this->_view->renderLayout();
    }

    /**
     * @return mixed
     */
    protected function getDtimeId()
    {
        return $this->getRequest()->getParam('id');
    }
}