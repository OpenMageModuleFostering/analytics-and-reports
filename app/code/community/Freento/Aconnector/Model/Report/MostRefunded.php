<?php
class Freento_Aconnector_Model_Report_MostRefunded extends Freento_Aconnector_Model_Report_Comparable
{
    protected $_sortField = 'qty';
    protected $_sortDirection = 'DESC';
    
    protected function _mysqlRequest()
    {
        
        $this->_fromParams = array(
            'percent' => 'IF(SUM(qty_invoiced), SUM(qty_refunded) / SUM(qty_invoiced) * 100, 0)',
            'qty' => 'sum(qty_refunded)',
            'total' => 'sum(base_amount_refunded)',
            'sku',
            'name'
        );
        
        $this->getSelect()
            ->from(array($this->_mainTablePrefix => $this->_getTable('sales/order_item')),
                $this->_fromParams
            )
            ->group('sku');
        $this->_prepareSort();

        return parent::_mysqlRequest();
    }
}
