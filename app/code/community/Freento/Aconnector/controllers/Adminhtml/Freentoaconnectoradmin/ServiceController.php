<?php

class Freento_Aconnector_Adminhtml_Freentoaconnectoradmin_ServiceController extends Mage_Adminhtml_Controller_Action
{
    
    public function connectAction()
    {
        $keyModel = Mage::getModel('freento_aconnector/keys');
        
        $publicKey = '';
        if(!$keyModel->isKeysGenerated()) {
            $publicKey = $keyModel->generateKeys();
        }
        
        $block = $this->getLayout()->createBlock('adminhtml/template', 'freento_aconnector.connection_form')->setTemplate('freento/aconnector/connect.phtml');
        
        $block->setData(array(
            'store_url' => $this->_getStoreUrl(),
            'username' => $keyModel->getUser()->getUsername(),
            'email' => $keyModel->getUser()->getEmail(),
            'user_id' => $keyModel->getUser()->getId(),
            'timezone' => Mage::getSingleton('core/date')->getGmtOffset('hours')
        ));
        
        if($publicKey) {
            $block->setData('public_key', $publicKey);
        }
        
        $this->getResponse()->setBody($block->toHtml());
    }
    
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('report/freento_aconnector');
    }
    
    protected function _getStoreUrl()
    {
        $defaultStoreId =  Mage::app()
            ->getWebsite(true)
            ->getDefaultGroup()
            ->getDefaultStoreId()
        ;
        
        return Mage::app()->getStore($defaultStoreId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);
    }
    
}