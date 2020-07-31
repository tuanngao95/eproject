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
 * Class General
 * @package MW\Ddate\Ui\DataProvider\View\Ddate\Modifier
 */
class General extends AbstractModifier implements ModifierInterface
{
    /**
     * @var bool
     */
    protected $_opened = true;
    /**
     * @var string
     */
    protected $_groupLabel = 'General Information';
    /**
     * @var int
     */
    protected $_sortOrder = 10;
    /**
     * @var string
     */
    protected $_groupContainer = 'general_information';

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
            'ddate' => $this->addFields('Delivery Date', 'input', 10, true),
            'dtimetext' => $this->addFields('Delivery Time', 'input', 20, true),
            'ordered' => $this->addFields('Current Booking', 'input', 30, true),
            'total_ordered' => $this->addFields('Total Booking', 'input', 40, true),
        ];
        return $children;
    }

    /**
     * @param string $label
     * @param string $formElement
     * @param int $sortOrder
     * @param bool $validation
     * @return array
     */
    protected function addFields($label, $formElement, $sortOrder, $validation = false)
    {
        $field = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => 'field',
                        'label' => __($label),
                        'dataType' => 'text',
                        'formElement' => $formElement,
                        'sortOrder' => $sortOrder,
                        'disabled'   => true
                    ],
                ],
            ],
        ];
        if ($validation)
            $field['arguments']['data']['config']['validation'] = ['required-entry' => true];
        return $field;
    }

    /**
     * modify data
     *
     * @return array
     */
    public function modifyData(array $data)
    {
        return array_replace_recursive(
            $data,
            $this->getData()
        );
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $itemId = $this->request->getParam('id');
        if (!empty($itemId)) {
            $item = $this->ddateFactory->create()->load($itemId);
            $this->loadedData[$item->getDdateId()] = $item->getData();
        }
        return $this->loadedData;
    }
}