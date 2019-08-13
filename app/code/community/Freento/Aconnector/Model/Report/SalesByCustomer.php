<?php
class Freento_Aconnector_Model_Report_SalesByCustomer extends Freento_Aconnector_Model_Report_Grouped_Abstract
{
    
    protected $_mainTablePrefix = 'orders';
    
    protected function _mysqlRequest()
    {
        
        $additionalParams = $this->getAdditionalParams();
        if (isset($additionalParams['customer_ids']) && $additionalParams['customer_ids']) {
            $this->_fromParams = array(
                'qty' => 'sum(qty_invoiced) - sum(qty_refunded)',
                'total' => 'sum(base_row_invoiced) - sum(base_amount_refunded)',
            );

            $this->_prepareGroup();
            
            $this->getSelect()->from(array($this->_mainTablePrefix => $this->_getTable('sales/order_item')), $this->_fromParams)
                ->joinLeft(
                    array('customers' => Mage::getSingleton('core/resource')->getTableName('customer/entity')),
                    'customers.entity_id = ' . $this->_mainTablePrefix . '.customer_id',
                    array()
                )
            ;
            
            $this->getSelect()->where('customers.entity_id IN (?)', explode(',', $additionalParams['customer_ids']));
            
            $this->_prepareSort();
        } else {
            $this->getSelect()
                ->from(array($this->_mainTablePrefix => $this->_getTable('sales/order')))
                ->where('0')
            ;
        }
        
        return parent::_mysqlRequest();
    }
    
}
