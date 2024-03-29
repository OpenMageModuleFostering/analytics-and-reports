<?php
class Freento_Aconnector_Model_Report_BestCategory extends Freento_Aconnector_Model_Report_Comparable
{
    
    protected $_compareAttribute = 'category_id';
    
    protected function _mysqlRequest()
    {
        
        $this->_fromParams = array(
            'qty' => 'sum(qty_invoiced) - sum(qty_refunded)',
            'total' => 'sum(base_row_invoiced) - sum(base_amount_refunded)',
        );
        
        $this->getSelect()
            ->from(array($this->_mainTablePrefix => $this->_getTable('sales/order_item')),
                $this->_fromParams
            )
            ->join(
                array('ccp' => Mage::getSingleton('core/resource')->getTableName('catalog/category_product')),
                'ccp.product_id = ' . $this->_mainTablePrefix . '.product_id',
                array()
            )
            ->join(
                array('ccev' =>Mage::getSingleton('core/resource')->getTableName('catalog_category_entity_varchar')),
                'ccev.entity_id = ccp.category_id',
                array('category_name_id' => 'CONCAT(value, " (ID: ", category_id, ")")')
            )
            ->group('ccp.category_id')
        ;
        $this->_prepareSort();
        
        return parent::_mysqlRequest();
    }
    
}