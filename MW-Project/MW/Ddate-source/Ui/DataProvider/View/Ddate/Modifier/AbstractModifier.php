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
use Magento\Framework\UrlInterface;

/**
 * Class AbstractModifier
 * @package MW\Ddate\Ui\DataProvider\View\Ddate\Modifier
 */
class AbstractModifier extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier
    implements ModifierInterface
{
    /**
     * Collapsible
     *
     * @var string
     */
    protected $_collapsible = true;

    /**
     * Group Container
     *
     * @var string
     */
    protected $_visible = true;

    /**
     * Group Container
     *
     * @var string
     */
    protected $_opened = true;

    /**
     * sort Sales
     *
     * @var string
     */
    protected $_sortOrder = '1';

    /**
     * Modifier Config
     *
     * @var array
     */
    protected $_modifierConfig = [];

    /**
     * Group Label
     *
     * @var string
     */
    protected $_groupLabel;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * is required
     *
     * @var boolean
     */
    protected $isRequried = true;

    /**
     * @var \MW\Ddate\Model\DdateFactory
     */
    protected $ddateFactory;

    /**
     * is required
     *
     * @var boolean
     */
    protected $visibleImage;

    const TMPL_INPUT = 'ui/form/element/input';
    const TMPL_TEXTAREA = 'ui/form/element/textarea';
    const TMPL_SELECT = 'ui/form/element/select';
    const TMPL_DATE = 'ui/form/element/date';

    /**
     * @param UrlInterface $urlBuilder
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \MW\Ddate\Model\DdateFactory $ddateFactory
     * @param array $_modifierConfig
     */
    public function __construct(
        UrlInterface $urlBuilder,
        \Magento\Framework\App\RequestInterface $request,
        \MW\Ddate\Model\DdateFactory $ddateFactory,
        array $modifierConfig = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->request = $request;
        $this->ddateFactory = $ddateFactory;
        $this->_modifierConfig = array_replace_recursive($this->_modifierConfig, $modifierConfig);
    }
    
    /**
     * set visible
     *
     * @param boolean
     * @return
     */
    public function setVisible($visible)
    {
        $this->_visible = $visible;
    }

    /**
     * get visible
     *
     * @param
     * @return
     */
    public function getVisible()
    {
        return $this->_visible;
    }

    /**
     * set opened
     *
     * @param boolean
     * @return
     */
    public function setOpened($opened)
    {
        $this->_opened = $opened;
    }

    /**
     * get opened
     *
     * @param
     * @return boolean
     */
    public function getOpened()
    {
        return $this->_opened;
    }

    /**
     * set collapsible
     *
     * @param boolean
     * @return
     */
    public function setCollapsible($collapsible)
    {
        $this->_collapsible = $collapsible;
    }

    /**
     * get collapsible
     *
     * @param
     * @return boolean
     */
    public function getCollapsible()
    {
            return $this->_collapsible;
    }

    /**
     * set group label
     *
     * @param boolean
     * @return
     */
    public function setGroupLabel($groupLabel)
    {
        $this->_groupLabel = $groupLabel;
    }

    /**
     * get group label
     *
     * @param
     * @return boolean
     */
    public function getGroupLabel()
    {
            return $this->_groupLabel;
    }

    /**
     * set sort order
     *
     * @param boolean
     * @return
     */
    public function setSortOrder($sortOrder)
    {
        $this->_sortOrder = $sortOrder;
    }

    /**
     * get is required
     *
     * @param
     * @return
     */
    public function getIsRequired()
    {
        return $this->isRequried;
    }

    /**
     * set is required
     *
     * @param boolean
     * @return
     */
    public function setIsRequired($isRequired)
    {
        $this->isRequried = $isRequired;
    }

    /**
     * get sort order
     *
     * @param
     * @return
     */
    public function getSortOrder()
    {
        return $this->_sortOrder;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyMeta(array $meta)
    {
        return $meta;
    }

}
