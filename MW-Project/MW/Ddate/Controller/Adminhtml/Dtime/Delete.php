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


/**
 * Class Delete
 * @package MW\Ddate\Controller\Adminhtml\Dtime
 */
class Delete extends \MW\Ddate\Controller\Adminhtml\AbstractController
{
    /**
     * @return void
     */
    public function execute()
    {
        $dtimeId = $this->getRequest()->getParam('id', null);

        if ($dtimeId) {
            $model = $this->_objectManager->create('MW\Ddate\Model\Dtime')->load($dtimeId);
            $model->delete();
        }

        $this->_redirect('*/*/');
    }
}
