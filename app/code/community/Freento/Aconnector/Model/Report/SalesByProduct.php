<?php
class Freento_Aconnector_Model_Report_SalesByProduct extends Freento_Aconnector_Model_Report_Grouped_Abstract
{    
    protected function _mysqlRequest()
    {
        $additionalParams = $this->getAdditionalParams();
        if (isset($additionalParams['skus']) && $additionalParams['skus']) {
            $this->_fromParams = array(
                'qty' => 'sum(qty_invoiced) - sum(qty_refunded)',
                'total' => 'sum(qty_invoiced*price) - sum(qty_refunded*price) - sum(' . $this->_mainTablePrefix . '.base_discount_invoiced)'
            );
            
            $this->_prepareGroup();
            
            $this->getSelect()
                ->from(array($this->_mainTablePrefix => $this->_getTable('sales/order_item')), $this->_fromParams)
                ->where('sku IN (?)', explode(',', $additionalParams['skus']))
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