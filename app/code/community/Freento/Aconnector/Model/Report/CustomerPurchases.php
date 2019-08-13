<?php
class Freento_Aconnector_Model_Report_CustomerPurchases extends Freento_Aconnector_Model_Report_Abstract
{
    
    protected function _mysqlRequest()
    {
        $attribute = Mage::getModel('eav/entity')->setType('catalog_product')->getAttribute('name');
        $this->getSelect()
            ->from(array($this->_mainTablePrefix => $this->_getTable('sales/order_item')))
            ->joinLeft(
                array('orders' => $this->_getTable('sales/order')),
                'orders.entity_id = e.order_id',
                array('increment_id')
            )
            ->join(
                array('products' => Mage::getSingleton('core/resource')->getTableName('catalog/product')),
                $this->_mainTablePrefix . '.product_id = products.entity_id',
                array()
            )
            ->joinLeft(
                array('at_name_default' => $attribute->getBackend()->getTable()), 
                "at_name_default.entity_id = {$this->_mainTablePrefix}.product_id AND at_name_default.attribute_id = {$attribute->getId()} AND at_name_default.store_id = 0", 
                array('product_info' => new Zend_Db_Expr('IF(at_name_store.value_id > 0, at_name_store.value, at_name_default.value)'))
            )
            ->joinLeft(
                array('at_name_store' => $attribute->getBackend()->getTable()), 
                "at_name_store.entity_id = e.product_id AND at_name_store.attribute_id = {$attribute->getId()}", 
                array()
            )
            ->join(
                array('payments' => $this->_getTable('sales/order_payment')),
                'orders.entity_id = payments.parent_id',
                array('method')
            )
            ->where('at_name_store.store_id IN (?)', $this->getStoreIds())
            ->where($this->_mainTablePrefix . '.parent_item_id is NULL')
            ->group('order_id')
        ;
        $this->_prepareSort();
        
        $additionalParams = $this->getAdditionalParams();
        if (isset($additionalParams['email']) && $additionalParams['email']) {
            $this->getSelect()->where('orders.customer_email = ?', $additionalParams['email']);
        }
        
        return parent::_mysqlRequest();
    }
    
}
