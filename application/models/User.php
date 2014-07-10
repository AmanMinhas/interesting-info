<?php

class Application_Model_User extends Zend_Db_Table_Abstract
{
	protected $_name = "User";
		
	public function createUser($arr) {
		$this->insert($arr);
	}
	
	public function updateUser($set,$condition = "1=1") {
		$where = $this->getAdapter()->quoteInto($condition);
		$this->update($set,$where);
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
