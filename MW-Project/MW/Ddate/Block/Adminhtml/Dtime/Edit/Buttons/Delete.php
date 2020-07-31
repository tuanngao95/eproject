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

namespace MW\Ddate\Block\Adminhtml\Dtime\Edit\Buttons;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\View\Element\UiComponent\Context as UiContext;

/**
 * Class Delete
 * @package MW\Ddate\Block\Adminhtml\Dtime\Edit\Buttons
 */
class Delete extends \Magento\Backend\Block\Template implements ButtonProviderInterface
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var UiContext
     */
    protected $uiContext;

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context,
        UiContext $uiContext
    ) {
        $this->context = $context;
        $this->uiContext = $uiContext;
    }

    /**
     * get button data
     *
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
        if ($Id = $this->context->getRequest()->getParam('id')) {
            $url = $this->uiContext->getUrl('*/*/delete', ['id'=>$Id]);
            $data = [
                'label' => __('Delete'),
                'class' => 'delete',
                'on_click' => sprintf("deleteConfirm(
                    'Are you sure you want to delete this Time?', 
                    '%s'
                )", $url),
                'sort_order' => 20,
            ];
        }
        return $data;
    }
}