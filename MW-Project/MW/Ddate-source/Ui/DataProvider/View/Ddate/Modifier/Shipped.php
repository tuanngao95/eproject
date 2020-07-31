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

namespace MW\Ddate\Ui\DataProvider\View\Ddate\Modifier;

use Magento\Ui\DataProvider\Modifier\ModifierInterface;

/**
 * Class Shipped
 * @package MW\Ddate\Ui\DataProvider\View\Ddate\Modifier
 */
class Shipped extends AbstractModifier implements ModifierInterface
{
    /**
     * @var bool
     */
    protected $_opened = false;
    /**
     * @var string
     */
    protected $_groupLabel = 'Shipped Orders';
    /**
     * @var int
     */
    protected $_sortOrder = 30;
    /**
     * @var string
     */
    protected $_groupContainer = 'shipped';

    /**
     * {@inheritdoc}
     */
    public function modifyMeta(array $meta)
    {
        $meta = array_replace_recursive(
            $meta,
            [
                $this->_groupContainer => [
                    'children' => $this->getGeneralChildren(),
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'label' => __($this->_groupLabel),
                                'collapsible' => true,
                                'visible' => $this->_visible,
                                'opened' => $this->getOpened(),
                                'componentType' => 'fieldset',
                                'sortOrder' => $this->_sortOrder,
                                'formSubmitType' => 'ajax',
                            ],
                        ],
                    ],
                ],
            ]
        );
        return $meta;
    }

    /**
     * Retrieve child meta configuration
     *
     * @return array
     */
    protected function getGeneralChildren()
    {
        $children = [
            'list_order_shipped' => $this->getListOrderShipped(),
        ];
        return $children;
    }

    protected function getListOrderShipped()
    {
        $listingTarget = 'mw_ddate_schedule_shipped';
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'autoRender' => true,
                        'componentType' => 'insertListing',
                        'dataScope' => $listingTarget,
                        'externalProvider' => $listingTarget . '.' . $listingTarget . '_data_source',
                        'ns' => $listingTarget,
                        'render_url' => $this->urlBuilder->getUrl('mui/index/render'),
                        'realTimeLink' => true,
                        'dataLinks' => [
                            'imports' => false,
                            'exports' => true
                        ],
                        'behaviourType' => 'simple',
                        'externalFilterMode' => true,
                        'imports' => [
                            'ddateId' => '${ $.provider }:data.ddate_id',
                        ],
                        'exports' => [
                            'ddateId' => '${ $.externalProvider }:params.ddate_id',
                        ]
                    ],
                ],
            ],
        ];
    }
}