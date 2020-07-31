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

namespace MW\Ddate\Controller\Checkout;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\ResponseInterface;

/**
 * Class UpdateComment
 * @package MW\Ddate\Controller\Checkout
 */
class UpdateComment extends Action
{

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \MW\Ddate\Helper\Config
     */
    protected $configHelper;

    /**
     * @var \MW\Ddate\Model\DtimeFactory
     */
    protected $dtimeModelFactory;

    /**
     * SelectTime constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \MW\Ddate\Helper\Config $configHelper
     * @param \MW\Ddate\Model\DtimeFactory $dtimeModelFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \MW\Ddate\Helper\Config $configHelper,
        \MW\Ddate\Model\DtimeFactory $dtimeModelFactory
    ) {
        parent::__construct($context);
        $this->checkoutSession = $checkoutSession;
        $this->configHelper = $configHelper;
        $this->dtimeModelFactory = $dtimeModelFactory;
    }

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
        $dcomment = $this->getRequest()->getParam('dcomment', null);
        $this->checkoutSession->setData('mw_delivery_comment', $dcomment);

        $jsonHelper = $this->_objectManager->create("Magento\Framework\Json\Helper\Data");
        $this->getResponse()->setBody($jsonHelper->jsonEncode($dcomment));
    }
}