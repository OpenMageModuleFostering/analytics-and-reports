<?php

class Freento_Aconnector_Block_Adminhtml_System_Config_Form_Disconnect extends Mage_Adminhtml_Block_System_Config_Form_Field
{

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $element->unsScope()
            ->unsCanUseWebsiteValue()
            ->unsCanUseDefaultValue()
        ;
        return parent::render($element);
    }
    
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $keyModel = Mage::getModel('freento_aconnector/keys');
        if(!$keyModel->isKeysGenerated()) {
            return '';
        }
        
        $this->setElement($element);
        $url = $this->getUrl('adminhtml/aconnector_system_config/disconnect');

        $html = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setType('button')
            ->setClass('scalable')
            ->setLabel(Mage::helper('freento_aconnector')->__('Disconnect'))
            ->setOnClick("setLocation('$url');")
            ->toHtml();

        return $html;
    }
    
}