<?php

class Freento_Aconnector_Model_Customer
{
    
    public function getCustomersList($params)
    {
        $result = array('main' => array(), 'count' => 0);
        
        if (empty($params)) {
            return $result;
        }
        
        $firstnameAttr = Mage::getModel('eav/entity_attribute')->loadByCode('1', 'firstname');
        $lastnameAttr = Mage::getModel('eav/entity_attribute')->loadByCode('1', 'lastname');
        
        $collection = Mage::getModel('customer/customer')->getCollection();
        $collection->addAttributeToSelect('email');
        
        $collection->getSelect()
             ->joinLeft(array('ce1' => Mage::getSingleton('core/resource')->getTableName('customer_entity_varchar')), 'ce1.entity_id = e.entity_id', array())
            ->where('ce1.attribute_id = ' . $firstnameAttr->getAttributeId())
            ->joinLeft(array('ce2' => Mage::getSingleton('core/resource')->getTableName('customer_entity_varchar')), 'ce2.entity_id = e.entity_id', array())
            ->where('ce2.attribute_id = ' . $lastnameAttr->getAttributeId())
            ->columns(new Zend_Db_Expr('CONCAT(ce1.value, \' \',ce2.value) AS name'))
        ;
        
        $collection
            ->setPageSize($params['limit'])
            ->setCurPage($params['page']);
        
        if (isset($params['query'])) {
            $collection->addAttributeToFilter(
                array(
                    array('attribute'=> 'email', 'like' => '%'. $params['query'] . '%')
                )
            );
        }
        
        foreach ($collection as $customer) {
            $result['main'][] = array(
                'id' => $customer->getId(),
                'email' => $customer->getEmail(),
                'name' => $customer->getData('name')
            );
        }
        $result['count'] = $collection->getSize();
        return $result;
    }
    
    public function getAttributes()
    {
        $attributes = Mage::getModel('customer/customer')->getAttributes();
        
        $result = array(
            'name' => array(
                'label' => 'Name',
                'type' => 'string'
            )
        );
        foreach ($attributes as $attribute) {
            
            if(!$attribute->getFrontendLabel()) {
                continue;
            }
            
            $type = '';
            /** @todo add type for selects */
            switch($attribute->getFrontendInput()) {
                case 'text':
                case 'textarea':
                    $type = 'string';
                    break;
                case 'date':
                case 'datetime':
                    $type = 'datetime';
                    break;
                default:
                    $type = 'string';
            }
            
            $result[$attribute->getAttributeCode()] = array(
                'label' => $attribute->getFrontendLabel(),
                'type' => $type
            );
        }
        
        if(isset($result['default_billing'])) {
            unset($result['default_billing']);
        }
        if(isset($result['default_shipping'])) {
            unset($result['default_shipping']);
        }
        
        $addressesAttributes = array(
            'billing_street' => array(
                'label' => 'Default Billing: Street',
                'type' => 'string'
            ),
            'billing_postcode' => array(
                'label' => 'Default Billing: Postcode',
                'type' => 'string'
            ),
            'billing_city' => array(
                'label' => 'Default Billing: City',
                'type' => 'string'
            ),
            'billing_telephone' => array(
                'label' => 'Default Billing: Telephone',
                'type' => 'string'
            ),
            'billing_fax' => array(
                'label' => 'Default Billing: Fax',
                'type' => 'string'
            ),
            'billing_region' => array(
                'label' => 'Default Billing: Region',
                'type' => 'string'
            ),
            'billing_country_code' => array(
                'label' => 'Default Billing: Country Code',
                'type' => 'string'
            ),
            'shipping_street' => array(
                'label' => 'Default Shipping: Street',
                'type' => 'string'
            ),
            'shipping_postcode' => array(
                'label' => 'Default Shipping: Postcode',
                'type' => 'string'
            ),
            'shipping_city' => array(
                'label' => 'Default Shipping: City',
                'type' => 'string'
            ),
            'shipping_telephone' => array(
                'label' => 'Default Shipping: Telephone',
                'type' => 'string'
            ),
            'shipping_fax' => array(
                'label' => 'Default Shipping: Fax',
                'type' => 'string'
            ),
            'shipping_region' => array(
                'label' => 'Default Shipping: Region',
                'type' => 'string'
            ),
            'shipping_country_code' => array(
                'label' => 'Default Shipping: Country Code',
                'type' => 'string'
            ),
        );
        
        return array_merge($result, $addressesAttributes);
    }
    
    public function getAttributesList()
    {
        $toReturn = array();
        foreach ($this->getAttributes() as $attributeCode => $attribute) {
            $toReturn[] = array(
                'id' => $attributeCode,
                'attribute_code' => $attributeCode,
                'attribute_label' => $attribute['label'],
                'attribute_type' => $attribute['type']
            );
        }
        return array('main' => $toReturn);
    }
    
}