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
 * Class Save
 * @package MW\Ddate\Controller\Adminhtml\Dtime
 */
class Save extends \MW\Ddate\Controller\Adminhtml\AbstractController
{
    /**
     * @return void
     */
    public function execute()
    {
        if ($data = $this->getRequest()->getPostValue()) {
            if (isset($data['store_ids'])) {
                $data['store_ids'] = implode(",", $data['store_ids']);
            }

            $dtimeId = $this->getRequest()->getParam('dtime_id', null);

            if ($dtimeId) {
                $model = $this->_objectManager->create('MW\Ddate\Model\Dtime')->load($dtimeId);
            } else {
                $model = $this->_objectManager->create('MW\Ddate\Model\Dtime');
                unset($data['dtime_id']);
            }

            try {
                $model->setData($data);
                $model->save();
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getDtimeId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (\Exception $e) {
                $this->_redirect('*/*/edit', array('id' => $dtimeId));
                return;
            }
        }

        $this->_redirect('*/*/');
    }
}
