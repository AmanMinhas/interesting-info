<?php
    class My_Auth_Adapter extends Zend_Auth_Adapter_DbTable
    {
        const BRUTE_FORCE       = "Account locked due to suspecious activity, please try in 15 mins";
        const LOGIN_SUCCESS     = "Authentication successful.";
        const INVALID_LOGIN     = "Supplied credential is invalid.";

        public function authenticate()
        {
            // load salt for the given identity
            $salt = $this->_zendDb->fetchOne("SELECT salt FROM {$this->_tableName} WHERE active IN (1) AND {$this->_identityColumn} = ?", $this->_identity);
            if (!$salt) {
                // return 'identity not found' error
                return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, $this->_identity);
            }

            // create the hash using the password and salt
            $hash = hash('sha512',$this->_credential,$salt); // SET THE PASSWORD HASH HERE USING $this->_credential and $salt
            // replace credential with new hash
            $this->_credential = $hash;

            $aml = new Application_Model_LoginSupport;
            $id = $this->_zendDb->fetchOne("SELECT id FROM {$this->_tableName} WHERE active IN (1) AND {$this->_identityColumn} = ?", $this->_identity);
            if($id) {
                if($aml->check_brute($id)) {
                    return new Zend_Auth_Result(self::BRUTE_FORCE, $this->_identity);
                }
            } 
            
            // Zend_Auth_Adapter_DbTable can do the rest now
            $p_auth = parent::authenticate();

            $success = false;
            foreach ($p_auth->getMessages() as $msg) {
                if($msg == self::LOGIN_SUCCESS) {
                    $success = true;
                    $aml->add_login_attempt($id,"success");
                }
            }
            if(!$success) {
                $aml->add_login_attempt($id,"failure");
            }

            return $p_auth;
        }
    }
?>