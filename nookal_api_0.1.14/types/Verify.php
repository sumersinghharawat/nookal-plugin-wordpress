<?php
/**
 * Created by PhpStorm.
 * User: James Fulton
 * Date: 7/12/2017
 * Time: 4:48 PM
 */
class Nookal_Verify extends Nookal_Response {
    
    public function __construct($config = NULL)
    {
        
        parent::__construct($config);
        
        if(isset($config['status']) && $config['status'] == true){
            if(isset($config['data'])){
                $this->isVerified($config['data']['results']['verify']);
            }           
        }else{
            $this->isVerified($config);
        }
        
    }
    
    public function isVerified($value = NULL){
        return $this->__build(__METHOD__, __FUNCTION__, $value);
    }

}