<?php
class Freento_Aconnector_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getExtensionVersion()
    {
        return Mage::getConfig()->getModuleConfig('Freento_Aconnector')->version;
    }
    
    public function isEnabled() {
        return parent::isModuleOutputEnabled() && Mage::getStoreConfig('aconnector/global/enabled');
    }
    
    public function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
    
    public function getSkuList($reportResult)
    {
        $skuList = array();
        foreach ($reportResult as $item) {
            if (isset($item['sku'])) {
                $skuList[] = $item['sku'];
            }
        }
        return $skuList;
    }
    
    public function getList($reportResult, $attribute)
    {
        $list = array();
        foreach ($reportResult as $item) {
            if (isset($item[$attribute])) {
                $list[] = $item[$attribute];
            }
        }
        return $list;
    }
    
    public function getParamList($reportResult, $param)
    {
        $skuList = array();
        foreach ($reportResult as $item) {
            if (isset($item[$param])) {
                $skuList[] = $item[$param];
            }
        }
        return $skuList;
    }
    
    public function getLogger()
    {
        return Mage::getSingleton('freento_aconnector/log');
    }
    
}