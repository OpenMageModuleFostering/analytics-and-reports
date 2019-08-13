<?php
class Freento_Aconnector_Model_Report_Sales extends Freento_Aconnector_Model_Report_Grouped_Abstract
{
    protected function _mysqlRequest()
    {
        $this->_fromParams = array(
            'qty' => 'sum(qty_invoiced) - sum(qty_refunded)',
            'total' => 'sum(base_row_invoiced) - sum(base_amount_refunded)',
        );
        
        $this->_prepareGroup();
        
        $this->getSelect()->from(array($this->_mainTablePrefix => $this->_getTable('sales/order_item')), $this->_fromParams);
        
        $this->_prepareSort();
        
        return parent::_mysqlRequest();
    }
    
}
