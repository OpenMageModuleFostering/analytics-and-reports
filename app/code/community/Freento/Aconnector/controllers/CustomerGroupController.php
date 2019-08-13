<?php
class Freento_Aconnector_CustomerGroupController extends Freento_Aconnector_Controller_Abstract
{
    public function attributesAction()
    {
        $response = json_encode(Mage::getModel('freento_aconnector/customer_group')->getAttributesList());
        
        $this->_prepareEncodedResponse($response);
    }
    
    public function listAction()
    {
        $response = json_encode(Mage::getModel('freento_aconnector/customer_group')->getCustomerGroupsList(Mage::app()->getRequest()->getParams()));
        
        $this->_prepareEncodedResponse($response);
    }
}
 