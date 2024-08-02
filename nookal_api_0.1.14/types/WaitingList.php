<?php


class Nookal_WaitingList_Element extends Nookal_Base {

    public function __construct($config = NULL)
    {

        if(!empty($config['ID'])){

            $this->ID($config['ID']);
            $this->clientID($config['clientID']);
            $this->locationID($config['locationID']);
            $this->preferredProviders($config['preferredProviders']);
            $this->serviceID($config['serviceID']);
            $this->classID($config['classID']);
            $this->fallback($config['fallback']);
            $this->notes($config['notes']);
            $this->priority($config['priority']);
            $this->onlineBooking($config['onlineBooking']);
            $this->active($config['active']);
            $this->created($config['created']);
            $this->lastModified($config['lastModified']);
            $this->requestedTimes($config['requestedTimes']);
            $this->requestedDays($config['requestedDays']);

        }

    }

    public function ID($value = NULL){
        return $this->__build(__METHOD__, __FUNCTION__, $value);
    }
    public function clientID($value = NULL){
        return $this->__build(__METHOD__, __FUNCTION__, $value);
    }
    public function locationID($value = NULL){
        return $this->__build(__METHOD__, __FUNCTION__, $value);
    }
    public function preferredProviders($value = NULL){
        return $this->__build(__METHOD__, __FUNCTION__, $value);
    }
    public function serviceID($value = NULL){
        return $this->__build(__METHOD__, __FUNCTION__, $value);
    }
    public function classID($value = NULL){
        return $this->__build(__METHOD__, __FUNCTION__, $value);
    }
    public function fallback($value = NULL){
        return $this->__build(__METHOD__, __FUNCTION__, $value);
    }
    public function notes($value = NULL){
        return $this->__build(__METHOD__, __FUNCTION__, $value);
    }
    public function priority($value = NULL){
        return $this->__build(__METHOD__, __FUNCTION__, $value);
    }
    public function onlineBooking($value = NULL){
        return $this->__build(__METHOD__, __FUNCTION__, $value);
    }
    public function active($value = NULL){
        return $this->__build(__METHOD__, __FUNCTION__, $value);
    }
    public function created($value = NULL){
        return $this->__build(__METHOD__, __FUNCTION__, $value);
    }
    public function lastModified($value = NULL){
        return $this->__build(__METHOD__, __FUNCTION__, $value);
    }
    public function requestedTimes($value = NULL){
        return $this->__build(__METHOD__, __FUNCTION__, $value);
    }
    public function requestedDays($value = NULL){
        return $this->__build(__METHOD__, __FUNCTION__, $value);
    }

}


class Nookal_WaitingList extends Nookal_Types {

    public function __construct($config = NULL)
    {

        parent::__construct($config);

        if(!empty($config['data']['results']['waitingList'])){

            $this->addChildren($config['data']['results']['waitingList']);

        }

    }

    public function addChildren($services){

        if(!empty($services)){

            foreach($services as $key=>$value){

                $this->addChild($value);

            }

        }

    }

    private function addChild($config){

        $child = new Nookal_WaitingList_Element($config);
        parent::setChild($child->ID(), $child);

    }

    public function count(){

        return count(parent::children());

    }

}