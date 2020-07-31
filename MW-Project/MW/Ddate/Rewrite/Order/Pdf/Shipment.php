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

namespace MW\Ddate\Rewrite\Order\Pdf;

class Shipment extends \Magento\Sales\Model\Order\Pdf\Shipment
{
    /**
     * Return PDF document
     *
     * @param \Magento\Sales\Model\Order\Shipment[] $shipments
     * @return \Zend_Pdf
     */
    public function getPdf($shipments = [])
    {
        $this->_beforeGetPdf();
        $this->_initRenderer('shipment');

        $pdf = new \Zend_Pdf();
        $this->_setPdf($pdf);
        $style = new \Zend_Pdf_Style();
        $this->_setFontBold($style, 10);
        foreach ($shipments as $shipment) {
            if ($shipment->getStoreId()) {
                $this->_localeResolver->emulate($shipment->getStoreId());
                $this->_storeManager->setCurrentStore($shipment->getStoreId());
            }
            $page = $this->newPage();
            $order = $shipment->getOrder();
            /* Add image */
            $this->insertLogo($page, $shipment->getStore());
            /* Add address */
            $this->insertAddress($page, $shipment->getStore());
            /* Add head */
            $this->insertOrder(
                $page,
                $shipment,
                $this->_scopeConfig->isSetFlag(
                    self::XML_PATH_SALES_PDF_SHIPMENT_PUT_ORDER_ID,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $order->getStoreId()
                )
            );
            /* Add document text and number */
            $this->insertDocumentNumber($page, __('Packing Slip # ') . $shipment->getIncrementId());
            $this->insertDelivery($page, $order);
            /* Add table */
            $this->_drawHeader($page);
            /* Add body */
            foreach ($shipment->getAllItems() as $item) {
                if ($item->getOrderItem()->getParentItem()) {
                    continue;
                }
                /* Draw item */
                $this->_drawItem($item, $page, $order);
                $page = end($pdf->pages);
            }
        }
        $this->_afterGetPdf();
        if ($shipment->getStoreId()) {
            $this->_localeResolver->revert();
        }
        return $pdf;
    }

    /**
     *
     * @param  \Zend_Pdf_Page $page
     * @param  object $order
     * @return void
     */
    public function insertDelivery(\Zend_Pdf_Page $page, $order)
    {
        $orderIncrementId = $order->getIncrementId();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $configHelper = $objectManager->create('MW\Ddate\Helper\Config');
        if ($configHelper->getModuleEnable()) {
            $ddateStore = $objectManager->create('MW\Ddate\Model\DdateStore')->getCollection()
                ->addFieldToFilter('increment_id', $orderIncrementId)
                ->getFirstItem();
            if ($ddateStore->getData()) {
                $ddate = $objectManager->create('MW\Ddate\Model\Ddate')->load($ddateStore->getDdateId());
                $ddatetext = __('Delivery Date:').' '.$ddate->getDdate();
                $dtimetext = __('Delivery Time:').' '.$ddate->getDtimetext();
                $page->setFillColor(new \Zend_Pdf_Color_GrayScale(1));
                $this->_setFontRegular($page, 10);
                $docHeader = $this->getDocHeaderCoordinates();
                $font = \Zend_Pdf_Font::fontWithPath(
                    $this->_rootDirectory->getAbsolutePath('lib/internal/GnuFreeFont/FreeSerif.ttf')
                );
                $feedDdate = 565 - $this->widthForStringUsingFontSize($ddatetext, $font, 10);
                $feedDtime = 565 - $this->widthForStringUsingFontSize($dtimetext, $font, 10);
                $page->drawText($ddatetext, $feedDdate, $docHeader[1] - 30, 'UTF-8');
                $page->drawText($dtimetext, $feedDtime, $docHeader[1] - 45, 'UTF-8');
            }
        }
    }
}