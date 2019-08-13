<?php
class Freento_Aconnector_Model_Report_NewCustomersByPeriod extends Freento_Aconnector_Model_Report_Grouped_Abstract
{
    
    protected $_mainTablePrefix = 'customers';
    
    protected function _mysqlRequest()
    {
        $this->_fromParams = array(
            'qty' => 'count(*)'
        );
        
        $this->_prepareGroup();
        
        $this->getSelect()
            ->from(array($this->_mainTablePrefix => Mage::getModel('customer/customer')->getResource()->getEntityTable()),
                $this->_fromParams
            )
        ;
        
        $this->_prepareSort();
        
        return parent::_mysqlRequest();
    }
    
}
