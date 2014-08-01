<?php

class Application_Model_LoginSupport extends Zend_Db_Table_Abstract
{

	public function login1($username,$password) {
		$db = new Zend_Db_Table(array("name"=>"User"));	
		
		if(!$this->user_exists($username)){
			return array("valid"=>false,"userExists"=>false,"msg"=>"Username does not exist");
		}
			
		$select = $db->select()
							->where("username = ?",$username)
							->where("pass = ?",$password);
		
		$row = $db->fetchAll($select)->toArray();
				
		//Check For Bruteforce.
		if($this->check_brute($row[0]['id'])) {
			return array("valid"=>false, "userExists"=>true, "msg"=>"More than 3 failed attempts in last 1 hr. Try again in 15 mins");
		}
				
		if(1==count($row)) {
			//Record login attempt
			$this->add_login_attempt($row[0]['id'],"success");
			
			//Login Succeeded.
			return array("valid"=>true, "userExists"=>true, "msg"=>"Authentication Successful");
		} else {
			//Record the failed login attempt.
			$user = new Application_Model_User;
			$userDetails = $user->getRowByUsername($username);
			
			$this->add_login_attempt($userDetails['id'],"failure");
			
			//Login Failed.
			return array("valid"=>false, "userExists"=>true, "msg"=>"Authentication Failed");
		}
		
	}
	
	/*
		If more than 3 invalid attempts in last 1 hour, block account for 5 mins
	*/
	public function check_brute($user_id) {
		$db = new Zend_Db_Table(array("name"=>"login_attempts"));	
		
		$select = $db->select()
							->where("user_id = ?",$user_id)
							->where("result = ?","failure")
							->where("time >= NOW() - INTERVAL 1 HOUR")
							->order("time desc");
		
		$row = $db->fetchAll($select);
		
		if(3<count($row)) { //it could be brute force.
		
			//Check if last attempt was less than 15 mins ago.
			$select = $db->select()
								->from($db,array( new Zend_Db_Expr('MAX(time) as last_attempt') ) )
								->where("user_id = ? ", $user_id)
								->group('user_id')
								->having('last_attempt > NOW() - INTERVAL 15 MINUTE');
			
			$row = $db->fetchAll($select);
			
			if(0 < count($row)) { // Brute force
				return true;
			} else {	// Not Brute force, last invalid attempt was more than 15 mins ago. 
				return false;
			}
						
		} else {	//it isn't brute force
			return false;	
		}							
							
	}
	
	public function add_login_attempt($user_id,$outcome) {
		$db = new Zend_Db_Table(array("name"=>"login_attempts"));	
		
		$attempt = array(
			"user_id"=>$user_id,
			"result" =>$outcome
		);
		
		$db->insert($attempt);
	}
	
	public function user_exists($username) {
		$db = new Zend_Db_Table(array("name"=>"User"));
		$select = $db->select()->where("username = ?",$username)	;
		$row = $db->fetchAll($select);
		
		if(1==count($row))
			return true;
		else 
			return false;
		
	}
}

