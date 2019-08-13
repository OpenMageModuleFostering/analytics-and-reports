<?php
class Freento_Aconnector_Model_Report_SalesByState extends Freento_Aconnector_Model_Report_Comparable
{
    protected $_sortField = 'region';
    protected $_sortDirection = 'ASC';
    protected $_compareAttribute = 'country_region';
    protected $_compareCondition = self::CONDITION_HAVING;

    protected function _mysqlRequest()
    {
        $this->_fromParams = array(
            'qty' => 'sum(qty_invoiced) - sum(qty_refunded)',
            'total' => 'sum(qty_invoiced * price) - sum(qty_refunded * price) - sum(base_discount_invoiced)'
        );
        
        $this->getSelect()
            ->from(
                array($this->_mainTablePrefix => $this->_getTable('sales/order_item')),
                $this->_fromParams
            )
            ->joinLeft(
                array('address' => $this->_getTable('sales/order_address')),
                'address.parent_id = ' . $this->_mainTablePrefix . '.order_id',
                array()
            )
            ->columns(new Zend_Db_Expr('IF(address.region IS NULL, address.country_id, CONCAT(address.country_id, \': \', address.region)) AS country_region'))
            ->group('country_region')
        ;
        $this->_prepareSort();
        
        return parent::_mysqlRequest();
    }

    protected function _prepareWhere()
    {
        $this->getSelect()->where('address.address_type = ?','billing');
        return parent::_prepareWhere();
    }
}
