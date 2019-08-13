<?php

class Freento_Aconnector_Adminhtml_Freentoaconnectoradmin_System_ConfigController extends Mage_Adminhtml_Controller_Action
{

    public function generateKeysAction()
    {
        try {
            $key = openssl_pkey_new(array(
                'private_key_bits' => 2048,
                'private_key_type' => OPENSSL_KEYTYPE_RSA,
            ));
            
            $privateKey = '';
            openssl_pkey_export($key, $privateKey);

            $publicKey = openssl_pkey_get_details($key);
            $publicKey = $publicKey['key'];
            Mage::getModel('core/config')->saveConfig('aconnector/global/generate_keys', $privateKey);

            openssl_free_key($key);
        } catch(Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        
        /**
         * @todo replace #URL# with correct analytics URL
         */
        $result = '<script type="text/javascript">var clipboard = new Clipboard("#freento_aconnector_api_key_btn");</script>';
        $result .= Mage::helper('freento_aconnector')->__('Here is your API key. Enter it in <a href="%s">Analytics service</a>.<pre><span id="freento_aconnector_api_key">%s</span></pre>', '#URL#', $publicKey);
        $result .= Mage::helper('freento_aconnector')->__('<button id="freento_aconnector_api_key_btn" data-clipboard-target="#freento_aconnector_api_key">%s</button>', 'Copy to clipboard');
        
        Mage::app()->getCacheInstance()->cleanType('config');
        Mage::dispatchEvent('adminhtml_cache_refresh_type', array('type' => 'config'));
        
        Mage::getSingleton('adminhtml/session')->addSuccess($result);
        
        $this->_redirectReferer();
    }

}
