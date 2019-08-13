<?php
class Freento_Aconnector_IndexController extends Freento_Aconnector_Controller_Abstract
{
    
    public function versionAction()
    {
        $this->getResponse()->setBody(Mage::helper('freento_aconnector')->getExtensionVersion());
    }
    
}
