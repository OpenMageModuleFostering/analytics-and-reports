<?php
class Freento_Aconnector_Model_Report_MostRefunded extends Freento_Aconnector_Model_Report_Comparable
{
    protected $_sortField = 'qty';
    protected $_sortDirection = 'DESC';
    
    protected function _mysqlRequest()
    {
        $this->getSelect()
            ->from(array($this->_mainTablePrefix => $this->_getTable('sales/order_item')),
                array(
                    'percent' => 'sum(qty_refunded) / sum(qty_invoiced) * 100',
                    'qty' => 'sum(qty_refunded)',
                    'total' => 'sum(base_amount_refunded)',
                    'sku',
                    'name'
                )
            )
            ->group('sku');
        $this->_prepareSort();

        return parent::_mysqlRequest();
    }
}
