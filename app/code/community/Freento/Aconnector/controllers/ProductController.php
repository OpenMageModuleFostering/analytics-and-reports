<?php
class Freento_Aconnector_ProductController extends Freento_Aconnector_Controller_Abstract
{
    public function attributesAction()
    {
        $response = json_encode(Mage::getModel('freento_aconnector/product')->getAttributesList());
        
        $this->_prepareEncodedResponse($response);
    }
    
    public function listAction()
    {
        $response = json_encode(Mage::getModel('freento_aconnector/product')->getProductsList(Mage::app()->getRequest()->getParams()));
        
        $this->_prepareEncodedResponse($response);
    }
}
 