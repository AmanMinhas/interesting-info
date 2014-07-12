<?php

class Application_Model_User extends Zend_Db_Table_Abstract
{
	protected $_name = "User";
		
	public function createUser($arr) {
		try {
			$this->insert($arr);
			$id = $this->getAdapter()->lastInsertId();
			return $id;
		} catch ( Zend_Exception $e ) {
			var_dump($e->getMessage());
		}
	}
	
	public function updateUser($set,$condition = array("1=1")) {
		
		$where = $this->getAdapter()->quoteInto($condition,"");
		
		try {
			$this->update($set,$where);
			return true;
		} catch (Zend_Exception $e) {
			var_dump($e->getMessage());
		}
	}
	
	public function getAll() {
		$select = $this->select();
		$rowSet = $this->fetchAll($select);
		return $rowSet->toArray();
	}
	
	public function getRowByUsername($username) {
		$select = $this->select()
								->where("username = ? ",$username);
		
		$user = $this->fetchRow($select);
		return $user->toArray();						
	}
	
	public function deleteUser($condition) {	
		$where = $this->getAdapter()->quoteInto($condition);
		$this->delete($where);
	}
	
	public function sendActivationEmail($id,$to) {
		$mail = new Zend_Mail();
		
		$htmlmessage = 
			"Thank You for signing up with It's Interesting. <br/> 
			Please activate your account by clicking at the button below <br>
			<a href = \"aman_proj/User/activate-account?id=".$id."\"><button class =\"btn btn-primary\">Activate Account</button></a>";

		$mail 	->addTo($to)
				->setFrom("aman.minhas16@gmail.com","Its Interesting")
				->setSubject("My Subject")
				->setBodyText("Some body msg")
				->setBodyHtml($htmlmessage)
				->send();
	}


	public static function isRegisteredUser($username) {

		$user = new Zend_Db_Table(array("name"=>"User"));
		
		$select = $user->select()->where("username = ?",$username)->limit(1);

		$row = $user->fetchRow($select);

		if(1==count($row)) {
			return true;
		}

		return false;

	}
}

?>
