<?php
abstract class Freento_Aconnector_Model_Report_Grouped_Abstract extends Freento_Aconnector_Model_Report_Abstract
{
    
    protected function _prepareGroup()
    {
        $additionalParams = $this->getAdditionalParams();
        if (isset($additionalParams['group_period']) && $additionalParams['group_period']) {
            switch ($additionalParams['group_period']){
                case 'hour': 
                    $this->_fromParams['hour'] = 'HOUR(' . $this->_getMainTablePrefix() . 'created_at)'; 
                    $groupBy = 'hour'; 
                    break;
                case 'weekday': 
                    $this->_fromParams['weekday'] = 'DAYNAME(' . $this->_getMainTablePrefix() . 'created_at)'; 
                    $groupBy = 'weekday';
                    break;
                case 'month': 
                    $this->_fromParams['month'] = 'MONTHNAME(' . $this->_getMainTablePrefix() . 'created_at)'; 
                    $groupBy = 'month';
                    break;
                case 'quarter': 
                    $this->_fromParams['quarter'] = 'QUARTER(' . $this->_getMainTablePrefix() . 'created_at)'; 
                    $groupBy = 'quarter'; 
                    break;
                case 'year': 
                    $this->_fromParams['year'] = 'YEAR(' . $this->_getMainTablePrefix() . 'created_at)'; 
                    $groupBy = 'year'; 
                    break;
                default: throw Exception('Invalid group period');
            }
            
            $this
                ->getSelect()
                ->group($groupBy)
            ;
        }
    }
}
