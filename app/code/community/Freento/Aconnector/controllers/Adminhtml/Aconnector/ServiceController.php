<?php

class Freento_Aconnector_Adminhtml_Aconnector_ServiceController extends Mage_Adminhtml_Controller_Action
{
    
    public function connectAction()
    {
        $keyModel = Mage::getModel('freento_aconnector/keys');
        
        $publicKey = '';
        if(!$keyModel->isKeysGenerated()) {
            $publicKey = $keyModel->generateKeys();
        }
        
        $adminSecret = Mage::helper('core')->encrypt($this->_getStoreUrl() . '#' . $keyModel->getUser()->getId());
        
        $block = $this->getLayout()->createBlock('adminhtml/template', 'freento_aconnector.connection_form')->setTemplate('freento/aconnector/connect.phtml');
        
        $block->setData(array(
            'store_url' => $this->_getStoreUrl(),
            'admin_secret' => $adminSecret,
            'admin_url' => Mage::helper('adminhtml')->getUrl('*/*/return'),
            'referer_url' => base64_encode($this->_getRefererUrl()),
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
    
    public function returnAction()
    {
        if(!$url = base64_decode($this->getRequest()->getParam('referer_url'))) {
            $url = Mage::helper('adminhtml')->getUrl();
        }
        $this->getResponse()->setRedirect($url);
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