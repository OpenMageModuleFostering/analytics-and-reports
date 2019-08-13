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
            'store_url' => Mage::getBaseUrl(),
            'admin_url' => Mage::helper('adminhtml')->getUrl('*/*/return'),
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
        $helper = Mage::helper('freento_aconnector');
        
        Mage::getSingleton('adminhtml/session')->addError($helper->__('We were unable to connect you to our service. Please, try again or contact support: <a class="email" href="mailto:support@analytics.freento.com">support@analytics.freento.com</a>'));
        
        $this->loadLayout();
        $this->renderLayout();
    }
    
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('report/freento_aconnector');
    }
    
    
}