<?php

class Freento_Aconnector_Adminhtml_Freentoaconnectoradmin_System_ConfigController extends Mage_Adminhtml_Controller_Action
{

    public function disconnectAction()
    {
        $keyModel = Mage::getModel('freento_aconnector/keys');
        $keyModel->delete();
        
        Mage::getSingleton('admin/session')->addSuccess(Mage::helper('freento_aconnector')->__('Analytics &amp; Reports service was successfully disconnected'));
        
        $this->_redirectReferer();
    }

}
