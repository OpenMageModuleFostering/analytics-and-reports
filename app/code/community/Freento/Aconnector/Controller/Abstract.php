<?php

class Freento_Aconnector_Controller_Abstract extends Mage_Core_Controller_Front_Action
{
    
    protected function _prepareEncodedResponse($body)
    {
        $encryptor = new Freento_Aconnector_Crypt();
        $keyModel = Mage::getModel('freento_aconnector/keys');
        
        return $this->getResponse()->setBody($encryptor->encrypt($keyModel->getPrivateKey(), $body, false));
    }
    
    
}