<?php
	class My_Validate_Text_Username extends Zend_Validate_Abstract {
		CONST NAME_TAKEN 	= "textNameTaken";
		CONST NOT_NAME 		= "notName";	 

		protected $_messageTemplates = array(
				self::NAME_TAKEN 	=> "Username already exist. Plese try another username",
				self::NOT_NAME 		=> "Input contains character not valid for a username"
			);

		public function isValid($value) {
			//Make sure the username doesn't already exist.
			if(Application_Model_User::isRegisteredUser($value)) {
				$this->_error(self::NAME_TAKEN);
				return false;
			}

			return true;
		}
	}
?>