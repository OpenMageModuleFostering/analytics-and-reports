<?php
class Freento_Aconnector_Model_Report_SalesByCustomerGroup extends Freento_Aconnector_Model_Report_Grouped_Abstract
{
    protected $_sortField = 'qty';
    protected $_sortDirection = 'DESC';
    
    protected $_mainTablePrefix = 'orders';
    
    protected function _mysqlRequest()
    {
        
        $additionalParams = $this->getAdditionalParams();
        if (isset($additionalParams['group_ids']) && $additionalParams['group_ids']) {
            $this->_fromParams = array(
                'total' => 'SUM(' . $this->_mainTablePrefix . '.base_grand_total)',
                'qty' => 'SUM(items.qty_ordered)',
            );

            $this->_prepareGroup();

            $this->getSelect(true)
                ->from(array($this->_mainTablePrefix => $this->_getTable('sales/order')), $this->_fromParams)
                ->joinLeft(array('groups' => $this->_getTable('customer/group')),
                    'groups.customer_group_id = ' . $this->_mainTablePrefix . '.customer_group_id',
                    array()
                )
                ->joinLeft(array('items' => $this->_getTable('sales/order_item')),
                    'items.order_id = ' . $this->_mainTablePrefix . '.entity_id',
                    array()
                )
                ->where('groups.customer_group_id IN (?)', explode(',', $additionalParams['group_ids']))
            ;
            
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
