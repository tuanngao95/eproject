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

namespace MW\Ddate\Block\Sales\Order;

/**
 * Class Ddate
 * @package MW\Ddate\Block\Sales\Order
 */
class Ddate extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $orderFactory;

    /**
     * @var \MW\Ddate\Model\DdateStoreFactory
     */
    protected $ddateStoreFactory;

    /**
     * @var \MW\Ddate\Model\DdateFactory
     */
    protected $ddateFactory;

    /**
     * Ddate constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param \MW\Ddate\Model\DdateFactory $ddateFactory
     * @param \MW\Ddate\Model\DdateStoreFactory $ddateStoreFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \MW\Ddate\Model\DdateFactory $ddateFactory,
        \MW\Ddate\Model\DdateStoreFactory $ddateStoreFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->request = $context->getRequest();
        $this->orderFactory = $orderFactory;
        $this->ddateFactory = $ddateFactory;
        $this->ddateStoreFactory = $ddateStoreFactory;
    }

    /**
     * @return bool|\MW\Ddate\Model\Ddate
     */
    public function getDdate()
    {
        $ddateStore = $this->ddateStoreFactory->create()->getCollection()
            ->addFieldToFilter('increment_id', $this->getIncrementId())
            ->getFirstItem();
        if($ddateStore->getData()){
            $ddate = $this->ddateFactory->create()->load($ddateStore->getDdateId());

            return $ddate;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->request->getParam('order_id');
    }

    /**
     * @return string
     */
    public function getIncrementId()
    {
        $order = $this->orderFactory->create()->load($this->getOrderId());

        return $order->getIncrementId();
    }
}