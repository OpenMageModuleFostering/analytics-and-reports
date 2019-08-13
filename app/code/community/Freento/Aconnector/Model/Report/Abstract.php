<?php
abstract class Freento_Aconnector_Model_Report_Abstract extends Mage_Core_Model_Abstract
{
    const MAX_PAGES_COUNT = 10000;
    const MAX_LIMIT = 1000;
    
    protected $_select;
    protected $_dateFrom;
    protected $_dateTo;
    protected $_page;
    protected $_limit;
    protected $_sortDirection;
    protected $_sortField;
    protected $_storeIds = array();
    protected $_mainReportResults;
    
    protected $_fromParams = array();
    protected $_mainTablePrefix = 'main_table';
    
    protected function _getMainTablePrefix()
    {
        if ($this->_mainTablePrefix) {
            return $this->_mainTablePrefix . '.';
        }
        return '';
    }
    
    protected function _helper()
    {
        return Mage::helper('freento_aconnector');
    }
    
    protected function _getTable($name)
    {
        return Mage::getResourceModel($name)->getMainTable();
    }
    
    public function setDateFrom($dateFrom)
    {
        if (!$this->_helper()->validateDate($dateFrom)) {
            throw new Exception('Incorrect date from');
        }
        $this->_dateFrom = $dateFrom;
        return $this;
    }
    
    public function getDateFrom()
    {
        return $this->_dateFrom;
    }
    
    public function setDateTo($dateTo)
    {
        if (!$this->_helper()->validateDate($dateTo)) {
            throw new Exception('Incorrect date to');
        }
        $this->_dateTo = $dateTo;
        
        /* Increase date to for 1 day, because end day should be included in select */
        $format = Freento_Aconnector_Helper_Data::DATETIME_FORMAT;
        $d = DateTime::createFromFormat($format, $dateTo);
        $this->_dateTo = $d->modify('+1 day')->format($format);
        
        return $this;
    }
    
    public function addDateFromTo($dateFrom, $dateTo) {
        if ($dateFrom && $dateTo) {
            $this
                ->setDateFrom($dateFrom)
                ->setDateTo($dateTo);
        }
        return $this;
    }
    
    public function getDateTo()
    {
        return $this->_dateTo;
    }
    
    public function setPage($page)
    {
        if (!is_integer($page) || $page <= 0 || $page > self::MAX_PAGES_COUNT) {
            throw new Exception('Incorrect page');
        }
        $this->_page = $page;
        return $this;
    }
    
    public function getPage()
    {
        return $this->_page;
    }
    
    public function setLimit($limit)
    {
        if (!is_integer($limit) || $limit <= 0 || $limit > self::MAX_LIMIT) {
            throw new Exception('Incorrect limit');
        }
        $this->_limit = $limit;
        return $this;
    }
    
    public function getLimit()
    {
        return $this->_limit;
    }
    
    public function setStoreIds($storeIds)
    {
        if (!is_array($storeIds)) {
            throw new Exception('Incorrect store ids');
        }
        $readyStoreIds = array();
        foreach($storeIds as $storeId) {
            $readyStoreIds[] = (int)$storeId;
        }
        $this->_storeIds = $readyStoreIds;
        return $this;
    }
    
    public function getStoreIds()
    {
        return $this->_storeIds;
    }
    
    public function setMainReportResults($mainReportResults)
    {
        $this->_mainReportResults = $mainReportResults;
        return $this;
    }
    
    public function getMainReportResults()
    {
        return $this->_mainReportResults;
    }
    
    public function setSortDirection($sortDirection)
    {
        if ($sortDirection) {
            $this->_sortDirection = $sortDirection;
        }
        return $this;
    }
    
    public function getSortDirection()
    {
        return $this->_sortDirection;
    }
    
    public function setSortField($sortField)
    {
        if ($sortField) {
            $this->_sortField = $sortField;
        }
        return $this;
    }
    
    public function getSortField()
    {
        return $this->_sortField;
    }
    
    public function process()
    {
        $this->_mysqlRequest();
        return $this->_processData();
    }
    
    public function getRecordsCount()
    {
        $this->_mysqlRequest();
        $this
            ->getSelect()
            ->reset(Zend_Db_Select::LIMIT_COUNT)
            ->reset(Zend_Db_Select::LIMIT_OFFSET);
        
        $countSelect = clone $this->getSelect();
        $countSelect->reset();
        $countSelect
            ->from(array('alias' => new Zend_Db_Expr(sprintf('(%s)', $this->getSelect()))), array())
            ->columns('COUNT(*)');
        
        Mage::helper('freento_aconnector')->getLogger()->log($countSelect->assemble());
        
        return Mage::getSingleton('core/resource')->getConnection('sales_read')->fetchOne($countSelect);
    }

    protected function _prepareWhere()
    {
        if ($this->getDateFrom() && $this->getDateTo()) {
            $this
                ->getSelect()
                ->where($this->_getMainTablePrefix() . "created_at >= ?", $this->getDateFrom())
                ->where($this->_getMainTablePrefix() . "created_at <= ?", $this->getDateTo());
        }
        
        $this->_addFilters();
        
        if (is_array($this->getStoreIds()) && !in_array(0, $this->getStoreIds())) {
            $this->getSelect()->where($this->_getMainTablePrefix() . 'store_id IN (?)', $this->getStoreIds() );
        }
        return $this->getSelect();
    }
    
    protected function _prepareSort()
    {
        if ($this->getSortField() && $this->getSortDirection()) {
            $this
                ->getSelect()
                ->order($this->getSortField() . ' ' . $this->getSortDirection());
        }
        return $this->getSelect();
    }

    protected function _mysqlRequest()
    {
        $this->_prepareWhere();
        
        Mage::helper('freento_aconnector')->getLogger()->log($this->getSelect()->assemble());
        
        return $this->getSelect();
    }

    protected function _processData() {
        return $this->getSelect()->query()->fetchAll();
    }


    public function getSelect() {
        if( !$this->_select) {
            $this->_select = Mage::getSingleton('core/resource')->getConnection('sales_read')->select();
            $this->_select->limitPage($this->getPage(), $this->getLimit());
        }
        return $this->_select;
    }
    
    protected function _addFilters()
    {
        $filters = $this->getFilter();
        
        if(!empty($filters)) {
            foreach($filters as $filter) {
                $this->_addFilter($filter);
            }
        }
    }
    
    protected function _addFilter($filter)
    {
        $operator = '';
        switch($filter->operator) {
            case 'lt':
                $operator = '<';
                break;
            case 'gt':
                $operator = '>';
                break;
            case 'eq':
                $operator = '=';
                break;
            case 'neq':
                $operator = '!=';
                break;
        }
        
        $this->getSelect()
            ->having("{$filter->property} {$operator} ?", $filter->value)
        ;
    }
}
