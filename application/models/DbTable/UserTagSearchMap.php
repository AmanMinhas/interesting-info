<?php

class Application_Model_DbTable_UserTagSearchMap extends Zend_Db_Table_Abstract
{

    protected $_name = 'UserTagSearchMap';

    public function getLastSearchedByUser($user_id) {

    	$select = $this	-> select()
    					-> where("user_id = ?",$user_id)
    					-> where("published = ?", 1);

        $rows 	= $this->fetchAll($select);
        
        if(!empty($rows)) 	
			return $rows;
    	else 				
            return array();
    }

    public function unpublish($where){

    	$set = array(
    			"published"			=> 0,
    			"date_unpublished"	=> new Zend_Db_Expr('NOW()') 
    		);

    	$this->update($set,$where);
    }
}

