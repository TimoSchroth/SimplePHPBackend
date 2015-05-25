<?php
class _speakingurl{
    private $aRequestsSplitted = array();
    private $aRequests = array();

    public function __construct($sRequestUri){
        $this->aRequestsSplitted = array_filter(explode('/', $sRequestUri));
    }

    private function __set($sName, $iKey){
        if (is_numeric($iKey)){
            $this->aRequests[$sName] = $this->aRequestsSplitted[$iKey];
        }
    }

    private function __get($sName){
        if (isset($sName)){
            return $this->aRequests[$sName];
        }
    }
}
