<?php
class Freento_Aconnector_Model_Report_Bestsellers extends Freento_Aconnector_Model_Report_Comparable
{
    protected $_sortField = 'total';
    protected $_sortDirection = 'DESC';
    
    protected function _mysqlRequest()
    {
        $this->getSelect()
            ->from(array($this->_mainTablePrefix => $this->_getTable('sales/order_item')),
                array(
                    'qty' => 'sum(qty_invoiced) - sum(qty_refunded)',
                    'total' => 'sum(qty_invoiced*price) - sum(qty_refunded*price) - sum(base_discount_invoiced)',
                    'sku',
                    'name'
                )
            )
            ->group('sku');
        $this->_prepareSort();
        
        return parent::_mysqlRequest();
    }
}
