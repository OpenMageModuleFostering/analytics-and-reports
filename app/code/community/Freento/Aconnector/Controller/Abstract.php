<?php

class Freento_Aconnector_Controller_Abstract extends Mage_Core_Controller_Front_Action
{
    
    protected function _prepareEncodedResponse($body)
    {
        $encryptor = new Freento_Aconnector_Crypt();
        $key = Mage::getStoreConfig('aconnector/global/generate_keys');
        return $this->getResponse()->setBody($encryptor->encrypt($key, $body, false));
    }
    
    
}