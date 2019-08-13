<?php
class Freento_Aconnector_Model_Report_BestCoupons extends Freento_Aconnector_Model_Report_Comparable
{
    protected $_compareAttribute = 'coupon_code';
    
    protected function _mysqlRequest()
    {
        $this->getSelect(true)
            ->from(array($this->_mainTablePrefix => $this->_getTable('sales/order')),
                array(
                    'coupon_code',
                    'qty' => 'COUNT(*)',
                    'total' => 'SUM(base_grand_total)',
                )
            )
            ->group('coupon_code')
        ;
        $this->_prepareWhere();
        
        $this->_prepareSort();
        
        return parent::_mysqlRequest();
    }

    protected function _prepareWhere()
    {
        $this->getSelect()->where('coupon_code IS NOT NULL');
        parent::_prepareWhere();
    }
}
