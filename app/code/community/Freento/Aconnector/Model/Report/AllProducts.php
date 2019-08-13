<?php
class Freento_Aconnector_Model_Report_AllProducts extends Freento_Aconnector_Model_Report_Abstract
{
    /**
     *
     * @var Mage_Catalog_Model_Resource_Product_Collection products collection
     */
    protected $_collection = null;
    
    protected function _getTable($name)
    {
        return Mage::getResourceModel($name)->getEntityTable();
    }
    
    /**
     * returns product collection instead of select
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    protected function _mysqlRequest()
    {
        if(!$this->_select) {
            $this->_collection = Mage::getModel('catalog/product')->getCollection()
                ->setPageSize($this->getLimit())
                ->setCurPage($this->getPage())
                ->addAttributeToSelect('*')
                ->addMinimalPrice()
                ->addFinalPrice()
                ->addTaxPercents()
                ->addCategoryIds()
                ->addUrlRewrite()
                ->joinAttribute(
                    'name',
                    'catalog_product/name',
                    'entity_id',
                    null,
                    'inner',
                    Mage_Core_Model_App::ADMIN_STORE_ID
                )
            ;
            
            $this->_select = $this->_collection->getSelect();
        }
        
        $this->_prepareSort();
        
        Mage::helper('freento_aconnector')->getLogger()->log($this->getSelect()->assemble());
    }
    
    protected function _processData() {
        $return = array();
        foreach ($this->_collection as $item) {
            $formattedData = array();
            foreach($item->getData() as $key => $record) {
                if (is_string($record)) {
                    $formattedData[$key] = htmlentities(substr($record, 0, 150));
                } elseif(is_null($record)) {
                    $formattedData[$key] = '';
                } else {
                    $formattedData[$key] = 'not available';
                }
            }
            $return[] = $formattedData;
        }
        return $return;
    }
    
    protected function _prepareSort()
    {
        if ($this->getSortField() && $this->getSortDirection()) {
            $this->_collection->addAttributeToSort($this->getSortField(), $this->getSortDirection());
        }
    }
    
}
