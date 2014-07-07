<?php

class LoginController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }
	
	public function authAction() {
		$username = "AmanMinhas";
		$password = hash('sha512',"password");
		
		$user = new Application_Model_LoginSupport;
		
		var_dump($user->login($username,$password));
#		var_dump($user->check_brute(4));
		
	}

}

