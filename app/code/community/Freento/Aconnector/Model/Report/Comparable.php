<?php

class Freento_Aconnector_Model_Report_Comparable extends Freento_Aconnector_Model_Report_Abstract
{
    
    const CONDITION_WHERE = 'where';
    const CONDITION_HAVING = 'having';
    
    protected $_compareCondition = self::CONDITION_WHERE;
    protected $_compareAttribute = 'sku';
    
    protected function _mysqlRequest()
    {
        $mainReportResults = $this->getMainReportResults();
        if (!empty($mainReportResults)) {
            if($this->_compareCondition == self::CONDITION_HAVING) {
                $this->getSelect()->having($this->_compareAttribute . ' IN (?)', $this->_helper()->getList($mainReportResults, $this->_compareAttribute));
            } else {
                $this->getSelect()->where($this->_compareAttribute . ' IN (?)', $this->_helper()->getList($mainReportResults, $this->_compareAttribute));
            }
        }
        
        return parent::_mysqlRequest();
    }
    
    /**
     * Sort Compare array based on Main results array
     * @return array
     */
    protected function _processData() {
        $result = $this->getSelect()->query()->fetchAll();
        if ($mainReportResults = $this->getMainReportResults()) {
            $toReturn = array();
            $list = $this->_helper()->getList($mainReportResults, $this->_compareAttribute);
            foreach ($result as $row) {
                $toReturn[array_search($row[$this->_compareAttribute], $list)] = $row;
            }
            $result = $toReturn;
        }
        return $result;
    }
    
}