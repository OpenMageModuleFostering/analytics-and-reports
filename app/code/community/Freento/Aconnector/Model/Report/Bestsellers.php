<?php
class Freento_Aconnector_Model_Report_Bestsellers extends Freento_Aconnector_Model_Report_Comparable
{
    protected $_sortField = 'total';
    protected $_sortDirection = 'DESC';
    
    protected function _mysqlRequest()
    {
        $this->_fromParams = array(
            'qty' => 'sum(qty_invoiced) - sum(qty_refunded)',
            'total' => 'sum(base_row_invoiced) - sum(base_amount_refunded)',
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
