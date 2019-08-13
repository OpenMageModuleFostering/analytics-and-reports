<?php
class Freento_Aconnector_Model_Report_BestCoupons extends Freento_Aconnector_Model_Report_Comparable
{
    protected $_compareAttribute = 'coupon_code';
    
    protected function _mysqlRequest()
    {
        $this->_fromParams = array(
            'coupon_code',
            'qty' => 'COUNT(*)',
            'total' => 'ABS(SUM(base_discount_amount))',
        );
        
        $this->getSelect(true)
            ->from(array($this->_mainTablePrefix => $this->_getTable('sales/order')),
                $this->_fromParams
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
