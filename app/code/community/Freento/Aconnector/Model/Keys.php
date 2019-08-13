<?php

class Freento_Aconnector_Model_Keys extends Mage_Core_Model_Abstract
{
    
    protected $_record = null;
    protected $_user = null;
    protected $_privateKey = null;
    
    protected function _construct()
    {
        $this->_init('freento_aconnector/keys');
        
        if($userId = Mage::app()->getRequest()->getParam('user_id', false)) {
            $this->_user = Mage::getModel('admin/user')->load($userId);
        } else {
            $this->_user = Mage::getSingleton('admin/session')->getUser();
        }
        
        $this->load($this->_user->getId(), 'user_id');
    }
    
    public function getUser()
    {
        return $this->_user;
    }
    
    public function getUserId()
    {
        return $this->_user->getId();
    }
    
    public function isKeysGenerated()
    {
        return $this->getPrivateKey() ? true : false;
    }
    
    public function generateKeys()
    {
        $publicKey = '';
        
        try {
            $key = openssl_pkey_new(array(
                'private_key_bits' => 2048,
                'private_key_type' => OPENSSL_KEYTYPE_RSA,
            ));
            
            $privateKey = '';
            openssl_pkey_export($key, $privateKey);

            $publicKey = openssl_pkey_get_details($key);
            $publicKey = $publicKey['key'];
            
            openssl_free_key($key);
            
            $this->setUserId($this->getUserId())
                ->setPrivateKey($privateKey)
                ->setPublicKey($publicKey)
                ->save();
            
        } catch(Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        
        return $publicKey;
    }
    
    public function setPrivateKey($key)
    {
        $encodedKey = Mage::helper('core')->encrypt($key);
        $this->setData('private_key', $encodedKey);
        
        return $this;
    }
    
    public function getPrivateKey()
    {
        $key = $this->getData('private_key');
        return Mage::helper('core')->decrypt($key);
        
    }
    
}