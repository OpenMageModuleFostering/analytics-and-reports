<?php
class Freento_Aconnector_Model_Report_SalesByMonth extends Freento_Aconnector_Model_Report_Abstract
{
    protected function _mysqlRequest()
    {
        $this->getSelect()
            ->from(array($this->_mainTablePrefix => $this->_getTable('sales/order_item')),
                array(
                    'month' => 'MONTH(created_at)',
                    'qty' => 'SUM(qty_invoiced) - SUM(qty_refunded)',
                    'total' => 'SUM(qty_invoiced*price) - SUM(qty_refunded*price) - SUM(base_discount_invoiced)',
                )
            )
            ->group('month')
            ->order('month ASC');
        
        return parent::_mysqlRequest();
    }
}