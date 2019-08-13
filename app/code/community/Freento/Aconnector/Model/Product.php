<?php

class Freento_Aconnector_Model_Product
{
    
    public function getProductsList($params)
    {
        $result = array('main' => array(), 'count' => 0);
        
        if (empty($params)) {
            return $result;
        }
        
        $collection = Mage::getModel('catalog/product')->getCollection();
        $collection->addAttributeToSelect('name');
        
        $collection
            ->setPageSize($params['limit'])
            ->setCurPage($params['page']);
        
        if (isset($params['query'])) {
            $collection->addAttributeToFilter(
                array(
                    array('attribute'=> 'sku', 'like' => '%'. $params['query'] . '%'),
                    array('attribute'=> 'name', 'like' => '%'. $params['query'] . '%')
                )
            );
        }
        
        foreach ($collection as $product) {
            $result['main'][] = array(
                'id' => $product->getId(),
                'product_name' => $product->getName(),
                'sku' => $product->getSku()
            );
        }
        $result['count'] = $collection->getSize();
        return $result;
    }
    
    public function getAttributes()
    {
        $attributes = Mage::getModel('catalog/product')->getAttributes();
        
        $result = array(
            'attribute_set_id' => array(
                'label' => 'Attribute Set',
                'type' => 'string'
            ),
            'websites' => array(
                'label' => 'Websites',
                'type' => 'string'
            ),
            'final_price' => array(
                'label' => 'Final Price',
                'type' => 'price'
            ),
            'min_price' => array(
                'label' => 'Minimal Price',
                'type' => 'price'
            ),
            'max_price' => array(
                'label' => 'Maximal Price',
                'type' => 'price'
            ),
        );
        
        foreach($attributes as $attr) {
            // skip attributes without label
            if(!$attr->getFrontendLabel()) {
                continue;
            }
            
            $type = '';
            /** @todo add type for selects */
            switch($attr->getFrontendInput()) {
                case 'text':
                case 'textarea':
                    $type = 'string';
                    break;
                case 'price':
                    $type = 'price';
                    break;
                case 'weight':
                    $type = 'numeric';
                    break;
                case 'date':
                case 'datetime':
                    $type = 'datetime';
                    break;
                default:
                    $type = 'string';
            }
            
            $result[$attr->getAttributeCode()] = array(
                'label' => $attr->getFrontendLabel(),
                'type' => $type
            );
        }
        
        return $result;
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