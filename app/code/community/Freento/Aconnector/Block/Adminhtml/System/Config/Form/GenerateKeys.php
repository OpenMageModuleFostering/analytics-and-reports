<?php

class Freento_Aconnector_Block_Adminhtml_System_Config_Form_GenerateKeys extends Mage_Adminhtml_Block_System_Config_Form_Field
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
        $this->setElement($element);
        $url = $this->getUrl('adminhtml/freentoaconnectoradmin_system_config/generateKeys');

        $html = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setType('button')
            ->setClass('scalable')
            ->setLabel(Mage::helper('freento_aconnector')->__('Generate Keys'))
            ->setOnClick("setLocation('$url');")
            ->toHtml();

        return $html;
    }
    
}