<?php
class Freento_Aconnector_Model_Report_SalesbyCountry extends Freento_Aconnector_Model_Report_Comparable
{
    protected $_sortField = 'qty';
    protected $_sortDirection = 'DESC';
    protected $_compareAttribute = 'country_id';
    
    protected function _mysqlRequest()
    {
        
        $this->_fromParams = array(
            'qty' => 'sum(qty_invoiced) - sum(qty_refunded)',
            'total' => 'sum(base_row_invoiced) - sum(base_amount_refunded)',
        );
        
        $this->getSelect()
            ->from(array($this->_mainTablePrefix => $this->_getTable('sales/order_item')), $this->_fromParams)
            ->joinLeft(
                array('address' => $this->_getTable('sales/order_address')),
                'address.parent_id = ' . $this->_mainTablePrefix . '.order_id',
                array('country_id')
            )
            ->group('address.country_id');
        $this->_prepareSort();

        return parent::_mysqlRequest();
    }

    protected function _prepareWhere()
    {
        $this->getSelect()->where('address.address_type = ?','billing');
        return parent::_prepareWhere();
    }
}
