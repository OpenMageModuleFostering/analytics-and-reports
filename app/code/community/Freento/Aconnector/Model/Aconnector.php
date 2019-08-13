<?php

class Freento_Aconnector_Model_Aconnector extends Mage_Core_Model_Abstract
{

    protected $_reports = array(
        'bestsellers',
        'sales',
        'salesByCountry',
        'bestCoupons',
        'bestCategory',
        'salesByCustomerGroup',
        'salesByCustomer',
        'newCustomersByPeriod',
        'salesByHour',
        'salesByMonth',
        'salesByDayOfWeek',
        'salesByWeek',
        'salesByQuarter',
        'salesByYear',
        'salesByPeriodOfTime',
        'mostRefunded',
        'salesDetailed',
        'allProducts',
        'allCustomers',
        'salesByProduct',
        'salesByCategory',
        'refundsPercent',
        'salesByState',
        'customerPurchases',
        'daysForTheFirstPurchase',
    );

    public static function methodIsAllowedForAproxy($method)
    {
        return in_array($method, array('getReportData', 'getStores'));
    }

    public static function getStores()
    {
        $options = array();
        $options[] = array(
            'label' => Mage::helper('adminhtml')->__('All Store Views'),
            'value' => 0,
            'level' => 0
        );

        $nonEscapableNbspChar = html_entity_decode('&#160;', ENT_NOQUOTES, 'UTF-8');

        foreach (Mage::app()->getWebsites() as $website) {
            $options[] = array(
                'label' => $website->getName(),
                'value' => implode(',', $website->getStoreIds()),
                'level' => 0
            );
            foreach ($website->getGroups() as $group) {
                if ($website->getId() != $group->getWebsiteId()) {
                    continue;
                }    
                $options[] = array(
                    'label' => $group->getName(),
                    'value' => implode(',', $group->getStoreIds()),
                    'level' => 1
                );
                foreach (Mage::app()->getStores() as $store) {
                    if ($group->getId() != $store->getGroupId()) {
                        continue;
                    }
                    $options[] = array(
                        'label' => $store->getName(),
                        'value' => $store->getId(),
                        'level' => 2
                    );
                }
            }
        }
        return $options;
    }
    
    public static function getReport($type)
    {
        if( ! Mage::getModel('freento_aconnector/aconnector')->validateReport($type)) {
            throw new Exception('Report model not found.');
        }
        
        return Mage::getModel('freento_aconnector/report_' . $type);
    }

    public static function getReportData($params)
    {
        $result = array();
        
        try {
            $reportType = $params['report_type'];
            $dateFrom = isset($params['date_from']) ? $params['date_from'] : null;
            $dateTo = isset($params['date_to']) ? $params['date_to'] : null;
            $dateFromCompare = isset($params['date_from_compare']) ? $params['date_from_compare'] : null;
            $dateToCompare = isset($params['date_to_compare']) ? $params['date_to_compare'] : null;
            $page = (int)$params['page'];
            $limit = (int)$params['limit'];
            $storeIds = isset($params['store_ids']) ? explode(',', $params['store_ids']) : array(0);
            $sortField = isset($params['sort_field']) ? $params['sort_field'] : null;
            $sortDirection = isset($params['sort_direction']) ? $params['sort_direction'] : null;
            $filter = isset($params['filter']) ? json_decode($params['filter']) : array();
            $groupPeriod = isset($params['group_period']) ? $params['group_period'] : null;
            
            $standardParams = array(
                'report_type',
                'date_from',
                'date_to',
                'date_from_compare',
                'date_to_compare',
                'page',
                'limit',
                'store_ids',
                'sort_field',
                'sort_direction',
                'filter'
            );
            
            $additionalParams = array();
            foreach($params as $key => $value) {
                if(!in_array($key, $standardParams)) {
                    $additionalParams[$key] = $value;
                }
            }
            
            $result['main'] = Freento_Aconnector_Model_Aconnector::getReport($reportType)
                                ->addDateFromTo($dateFrom, $dateTo)
                                ->setLimit($limit)
                                ->setPage($page)
                                ->setStoreIds($storeIds)
                                ->setSortDirection($sortDirection)
                                ->setSortField($sortField)
                                ->setFilter($filter)
                                ->setAdditionalParams($additionalParams)
                                ->process();
            
            if ($dateFromCompare && $dateToCompare) {
                $compare = Freento_Aconnector_Model_Aconnector::getReport($reportType)
                                        ->addDateFromTo($dateFromCompare, $dateToCompare)
                                        ->setLimit($limit)
                                        ->setPage(1)
                                        ->setStoreIds($storeIds)
                                        ->setMainReportResults($result['main'])
                                        ->setSortDirection($sortDirection)
                                        ->setSortField($sortField)
                                        ->setFilter($filter)
                                        ->setAdditionalParams($additionalParams)
                                        ->process();
                
                if(is_null($groupPeriod) || (empty($result['main']) && empty($compare))) {
                    foreach ($compare as $rowKey => $row) {
                        foreach ($row as $rowColumn => $column) {
                            $result['main'][$rowKey][$rowColumn . '_compare'] = $column;
                        }
                    }
                } else {
                    $result['main'] = self::_prepareGroupedCompareResult($groupPeriod, $result['main'], $compare);
                }
                
            }
            
            $result['count'] = Freento_Aconnector_Model_Aconnector::getReport($reportType)
                                ->setLimit($limit)
                                ->setPage($page)
                                ->setStoreIds($storeIds)
                                ->setSortDirection($sortDirection)
                                ->setSortField($sortField)
                                ->addDateFromTo($dateFrom, $dateTo)
                                ->setFilter($filter)
                                ->setAdditionalParams($additionalParams)
                                ->getRecordsCount();
        } catch (Exception $ex) {
            $result['error'] = $ex->getMessage();
        }
        return json_encode($result);
    }
    
    protected static function _prepareGroupedCompareResult($period, $result, $compare)
    {
        $emptyItem = array();
        $keys = empty($result) ? array_keys(reset($compare)) : array_keys(reset($result));
        foreach($keys as $key) {
            $emptyItem[$key] = '';
            $emptyItem[$key . '_compare'] = '';
        }
        
        $toReturn = array();
        foreach ($result as $row) {
            if(!isset($toReturn[$row[$period]])) {
                $toReturn[$row[$period]] = $emptyItem;
            }
            
            foreach($row as $k => $v) {
                $toReturn[$row[$period]][$k] = $v;
            }
        }
        foreach ($compare as $row) {
            if(!isset($toReturn[$row[$period]])) {
                $toReturn[$row[$period]] = $emptyItem;
            }
            
            foreach($row as $k => $v) {
                $toReturn[$row[$period]][$k . '_compare'] = $v;
            }
            
            if(!$toReturn[$row[$period]][$period]) {
                $toReturn[$row[$period]][$period] = $row[$period];
            }
        }
        
        return $toReturn;
    }
    
    public function validateReport($report)
    {
        //сопоставить имя модели со списком доступных, что бы не было фатала
        if( ! in_array($report, $this->_reports)) {
            return false;
        }
        return true;
    }
}
