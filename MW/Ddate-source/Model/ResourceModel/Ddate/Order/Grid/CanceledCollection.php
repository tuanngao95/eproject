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

namespace MW\Ddate\Model\ResourceModel\Ddate\Order\Grid;

/**
 * Class CanceledCollection
 * @package MW\Ddate\Model\ResourceModel\Ddate\Order\Grid
 */
class CanceledCollection extends \Magento\Sales\Model\ResourceModel\Order\Grid\Collection
{
    /**
     * {@inheritdoc}
     */
    protected function _initSelect()
    {
        parent::_initSelect();

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $request = $objectManager->get('Magento\Framework\App\RequestInterface');
        $ddateId = $request->getParam('ddate_id', false);
        if ($ddateId) {
            $incrementIds = [0];
            $ddateStores = $objectManager->create('MW\Ddate\Model\DdateStore')->getCollection()
                ->addFieldToFilter('ddate_id', array('eq' => $ddateId))
                ->addFieldToFilter('delivery_status', array('eq' => 3));
            if ($ddateStores->getSize()) {
                foreach ($ddateStores as $ddate) {
                    $incrementIds[] = $ddate->getIncrementId();
                }
            }
            $this->addFieldToFilter('main_table.increment_id', array('in', $incrementIds));
        }

        return $this;
    }
}