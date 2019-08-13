<?php

class Freento_Aconnector_Model_Customer_Group
{
    
    public function getCustomerGroupsList($params)
    {
        $result = array('main' => array(), 'count' => 0);
        
        if (empty($params)) {
            return $result;
        }
        
        $collection = Mage::getModel('customer/group')->getCollection();
        
        $collection
            ->setPageSize($params['limit'])
            ->setCurPage($params['page']);
        
        if (isset($params['query'])) {
            $collection->addFieldToFilter(
                'customer_group_code', array('like' => '%'. $params['query'] . '%')
            );
        }
        
        foreach ($collection as $customer) {
            $result['main'][] = array(
                'id' => $customer->getCustomerGroupId(),
                'customer_group_code' => $customer->getCustomerGroupCode()
            );
        }
        $result['count'] = $collection->getSize();
        return $result;
    }
    
    public function getAttributesList()
    {
        $toReturn = array(
            array(
                'attribute_code' => 'customer_group_id',
                'attribute_label' => 'Group ID',
                'attribute_type' => 'string'
            ),
            array(
                'attribute_code' => 'customer_group_code',
                'attribute_label' => 'Group Code',
                'attribute_type' => 'string'
            )
        );

        return array('main' => $toReturn);
    }
    
}