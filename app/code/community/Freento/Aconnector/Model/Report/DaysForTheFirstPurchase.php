<?php
class Freento_Aconnector_Model_Report_DaysForTheFirstPurchase extends Freento_Aconnector_Model_Report_Comparable
{

    protected $_mainTablePrefix = 'subselect';
    protected $_compareAttribute = 'total';
    protected $_compareCondition = self::CONDITION_HAVING;
    
    protected function _mysqlRequest()
    {
        
        // START prepare subselect
        $subselect = Mage::getSingleton('core/resource')->getConnection('sales_read')->select()
            ->from(
                array('e' => Mage::getSingleton('core/resource')->getTableName('customer/entity'))
            )
            ->join(
                array('orders' => $this->_getTable('sales/order')),
                'orders.customer_id = e.entity_id',
                array()
            )
            
            ->columns(new Zend_Db_Expr('DATEDIFF(orders.created_at, e.created_at) AS days'))
            
            ->group('e.entity_id')
            ->order('created_at', 'ASC')
            
        ;
        // END prepare subselect
        
        $this->getSelect()
            ->from(
                array($this->_mainTablePrefix => $subselect),
                array('total' => 'days', 'qty' => 'COUNT(days)')
            )
            ->group('days')
            ->order('days', 'DESC')
        ;
        
        $this->_prepareSort();    
        
        return parent::_mysqlRequest();
    }
    
}
