<?php
class Freento_Aconnector_Model_Report_AllCustomers extends Freento_Aconnector_Model_Report_Abstract
{
    protected function _getTable($name)
    {
        return Mage::getResourceModel($name)->getEntityTable();
    }
    
    protected function _mysqlRequest()
    {
        if(!$this->_select) {
            $this->_collection = Mage::getResourceModel('customer/customer_collection')
                ->addNameToSelect()
                ->addAttributeToSelect('*')

                ->joinAttribute('billing_street', 'customer_address/street', 'default_billing', null, 'left')
                ->joinAttribute('billing_postcode', 'customer_address/postcode', 'default_billing', null, 'left')
                ->joinAttribute('billing_city', 'customer_address/city', 'default_billing', null, 'left')
                ->joinAttribute('billing_telephone', 'customer_address/telephone', 'default_billing', null, 'left')
                ->joinAttribute('billing_fax', 'customer_address/fax', 'default_billing', null, 'left')
                ->joinAttribute('billing_region', 'customer_address/region', 'default_billing', null, 'left')
                ->joinAttribute('billing_country_code', 'customer_address/country_id', 'default_billing', null, 'left')

                ->joinAttribute('shipping_street', 'customer_address/street', 'default_shipping', null, 'left')
                ->joinAttribute('shipping_postcode', 'customer_address/postcode', 'default_shipping', null, 'left')
                ->joinAttribute('shipping_city', 'customer_address/city', 'default_shipping', null, 'left')
                ->joinAttribute('shipping_telephone', 'customer_address/telephone', 'default_shipping', null, 'left')
                ->joinAttribute('shipping_fax', 'customer_address/fax', 'default_shipping', null, 'left')
                ->joinAttribute('shipping_region', 'customer_address/region', 'default_shipping', null, 'left')
                ->joinAttribute('shipping_country_code', 'customer_address/country_id', 'default_shipping', null, 'left')

                ->joinAttribute('taxvat', 'customer/taxvat', 'entity_id', null, 'left')
            ;
            $this->_select = $this->_collection->getSelect();
            $this->_select->limitPage($this->getPage(), $this->getLimit());
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
