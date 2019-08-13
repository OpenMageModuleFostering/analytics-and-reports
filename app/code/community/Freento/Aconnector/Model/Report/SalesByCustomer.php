<?php
class Freento_Aconnector_Model_Report_SalesByCustomer extends Freento_Aconnector_Model_Report_Grouped_Abstract
{
    
    protected $_mainTablePrefix = 'orders';
    
    protected function _mysqlRequest()
    {
        
        $additionalParams = $this->getAdditionalParams();
        if (isset($additionalParams['customer_ids']) && $additionalParams['customer_ids']) {
            $this->_fromParams = array(
                'total' => 'SUM(' . $this->_mainTablePrefix . '.base_grand_total)',
                'qty' => 'SUM(items.qty_ordered)',
            );

            $this->_prepareGroup();
            
            $this->getSelect()
                ->from(array($this->_mainTablePrefix => $this->_getTable('sales/order')), $this->_fromParams)

                ->joinLeft(
                    array('items' => $this->_getTable('sales/order_item')),
                    'items.order_id = ' . $this->_mainTablePrefix . '.entity_id',
                    array()
                )
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
