<?php
    class My_Auth_Adapter extends Zend_Auth_Adapter_DbTable
    {
        public function authenticate()
        {
            // load salt for the given identity
            $salt = $this->_zendDb->fetchOne("SELECT salt FROM {$this->_tableName} WHERE active IN (0,1) AND {$this->_identityColumn} = ?", $this->_identity);
            if (!$salt) {
                // return 'identity not found' error
                return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, $this->_identity);
            }

            // create the hash using the password and salt
            $hash = hash('sha512',$this->_credential,$salt); // SET THE PASSWORD HASH HERE USING $this->_credential and $salt
            // replace credential with new hash
            $this->_credential = $hash;

            // Zend_Auth_Adapter_DbTable can do the rest now
            return parent::authenticate();
        }
    }
?>