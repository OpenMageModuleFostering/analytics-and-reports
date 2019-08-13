<?php
class Freento_Aconnector_Model_Report_SalesDetailed extends Freento_Aconnector_Model_Report_Abstract
{
    protected function _mysqlRequest()
    {
        $this->getSelect()
            ->from(array($this->_mainTablePrefix => $this->_getTable('sales/order')),
                array_keys(Mage::getModel('freento_aconnector/order')->getAttributes())
            )
        ;
        $this->_prepareSort();
        return parent::_mysqlRequest();
    }
}
