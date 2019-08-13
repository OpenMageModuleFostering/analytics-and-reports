<?php

class Freento_Aconnector_ReportController extends Freento_Aconnector_Controller_Abstract
{
    
    public function processAction()
    {
        $response = Freento_Aconnector_Model_Aconnector::getReportData(
                        $this->getRequest()->getParams()
        );
        
        $this->_prepareEncodedResponse($response);
    }
    
}