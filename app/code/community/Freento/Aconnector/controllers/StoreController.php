<?php

class Freento_Aconnector_StoreController extends Freento_Aconnector_Controller_Abstract
{
    
    public function listAction()
    {
        $result = array();
        $result[] = array('store_id' => 0, 'store_name' => 'Admin');
        foreach (Mage::app()->getStores() as $store) {
            $result[] = array('store_id' => $store->getId(), 'store_name' => $store->getName());
        }
        
        $this->_prepareEncodedResponse(json_encode($result));
    }
    
}