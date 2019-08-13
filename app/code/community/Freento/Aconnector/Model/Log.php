<?php

class Freento_Aconnector_Model_Log
{
    
    const LOG_FILE_NAME = 'freento_aconnector.log';
    
    protected $_enableTestsLog = false;
    protected $_log = array();
    protected $_isLogEnabled = null;
    
    public function getEnableTestsLog()
    {
        return $this->_enableTestsLog;
    }
    
    public function setEnableTestsLog($value)
    {
        $this->_enableTestsLog = $value;
    }
    
    public function getLog()
    {
        return $this->_log;
    }
    
    public function getLogRecord($key) {
        return isset($this->_log[$key]) ? $this->_log[$key] : false;
    }
    
    public function addRowToLog($value, $key = null)
    {
        if(is_null($key)) {
            $this->_log[] = $value;
        } else {
            $this->_log[$key] = $value;
        }
    }
    
    public function isLogEnabled()
    {
        if(is_null($this->_isLogEnabled)) {
            $this->_isLogEnabled = Mage::getStoreConfig('aconnector/global/log_enabled');
        }
        
        return $this->_isLogEnabled;
    }
    
    public function log($message, $key = null)
    {
        if($this->getEnableTestsLog()) {
            $this->addRowToLog($message, $key);
        }
        
        if($this->isLogEnabled()) {
            Mage::log($message, Zend_Log::DEBUG, self::LOG_FILE_NAME);
        }
    }
    
}