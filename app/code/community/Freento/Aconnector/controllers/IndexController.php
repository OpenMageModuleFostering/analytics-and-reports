<?php
class Freento_Aconnector_IndexController extends Freento_Aconnector_Controller_Abstract
{
    public function indexAction()
    {
        $response = Freento_Aconnector_Model_Aconnector::getReportData(
                        $this->getRequest()->getParams()
        );
        
        $this->_prepareEncodedResponse($response);
    }
    
    public function storesAction()
    {
        $result = array();
        $result[] = array('store_id' => 0, 'store_name' => 'Admin');
        foreach (Mage::app()->getStores() as $store) {
            $result[] = array('store_id' => $store->getId(), 'store_name' => $store->getName());
        }
        
        $this->_prepareEncodedResponse(json_encode($result));
    }
    
    public function versionAction()
    {
        $this->getResponse()->setBody(Mage::helper('freento_aconnector')->getExtensionVersion());
    }
    
}
