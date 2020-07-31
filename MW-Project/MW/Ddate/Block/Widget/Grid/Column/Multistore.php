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

namespace MW\Ddate\Block\Widget\Grid\Column;

/**
 * Class Multistore
 * @package MW\Ddate\Block\Widget\Grid\Column
 */
class Multistore extends \Magento\Backend\Block\Widget\Grid\Column\Multistore
{
    /**
     * Multistore constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(\Magento\Backend\Block\Template\Context $context, array $data = [])
    {
        if ($this->_rendererTypes) {
            $this->_rendererTypes['multistore'] = \MW\Ddate\Block\Widget\Grid\Column\Renderer\MultiStore::class;
        }
        if ($this->_filterTypes) {
            $this->_filterTypes['multistore'] = \MW\Ddate\Block\Widget\Grid\Column\Filter\MultiStore::class;
        }
        parent::__construct($context, $data);
    }
}