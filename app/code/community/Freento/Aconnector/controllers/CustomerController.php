<?php
class Freento_Aconnector_CustomerController extends Freento_Aconnector_Controller_Abstract
{
    public function attributesAction()
    {
        $response = json_encode(Mage::getModel('freento_aconnector/customer')->getAttributesList());
        
        $this->_prepareEncodedResponse($response);
    }
    
    public function listAction()
    {
        $response = json_encode(Mage::getModel('freento_aconnector/customer')->getCustomersList(Mage::app()->getRequest()->getParams()));
        
        $this->_prepareEncodedResponse($response);
    }
}
 