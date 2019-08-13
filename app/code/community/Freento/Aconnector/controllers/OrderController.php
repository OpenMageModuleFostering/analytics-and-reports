<?php
class Freento_Aconnector_OrderController extends Freento_Aconnector_Controller_Abstract
{
    public function attributesAction()
    {
        $response = json_encode(Mage::getModel('freento_aconnector/order')->getAttributesList());
        
        $this->_prepareEncodedResponse($response);
    }
}
 
