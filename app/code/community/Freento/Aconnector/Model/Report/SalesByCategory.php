<?php
class Freento_Aconnector_Model_Report_SalesByCategory extends Freento_Aconnector_Model_Report_Abstract
{
    protected function _mysqlRequest()
    {
        $additionalParams = $this->getAdditionalParams();
        if (isset($additionalParams['category_id']) && $additionalParams['category_id']) {
            $this->getSelect()
                ->from(array($this->_mainTablePrefix => $this->_getTable('sales/order_item')),
                    array(
                        'qty' => 'sum(qty_invoiced) - sum(qty_refunded)',
                        'total' => 'sum(qty_invoiced*price) - sum(qty_refunded*price) - sum(' . $this->_mainTablePrefix . '.base_discount_invoiced)',
                        'sku',
                        'name',
                    )
                )
                ->joinLeft(
                    array('ccp' => Mage::getSingleton('core/resource')->getTableName('catalog/category_product')),
                    'ccp.product_id = ' . $this->_mainTablePrefix . '.product_id',
                    array()
                )
                ->joinLeft(
                    array('so' => $this->_getTable('sales/order')),
                    'so.entity_id = ' . $this->_mainTablePrefix . '.order_id',
                    array('increment_id')
                )
                ->where('ccp.category_id = ?', $additionalParams['category_id'])
                ->group('order_id')
            ;
            $this->_prepareSort();
        } else {
            $this->getSelect()
                ->from(array($this->_mainTablePrefix => $this->_getTable('sales/order_item')))
                ->where('0')
            ;
        }
        
        return parent::_mysqlRequest();
    }
    
}