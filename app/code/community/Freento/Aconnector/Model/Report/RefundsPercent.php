<?php
class Freento_Aconnector_Model_Report_RefundsPercent extends Freento_Aconnector_Model_Report_Grouped_Abstract
{
    protected function _mysqlRequest()
    {
        $this->_fromParams = array(
            'refunds_percent' => 'IF(SUM(qty_invoiced), SUM(qty_refunded) / SUM(qty_invoiced) * 100, 0)',
            'total_invoiced' => 'SUM(row_invoiced)',
            'total_refunded' => 'SUM(amount_refunded)'
        );
        
        $this->_prepareGroup();
        
        $this->getSelect()
            ->from(array($this->_mainTablePrefix => $this->_getTable('sales/order_item')),
                $this->_fromParams
            )
        ;
        
        $this->_prepareSort();
        
        return parent::_mysqlRequest();
    }
    
}