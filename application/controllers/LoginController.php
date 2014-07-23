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

	  	// $this->_helper->layout->disableLayout();
		if(Zend_Auth::getInstance()->hasIdentity()){
			$this->_redirect('User/home');
		}
		
		$loginForm = new Application_Form_LoginForm;
		$user = new Application_Model_LoginSupport;
		
		$request = $this->getRequest();
		// if($_POST) {
		if($request->isPost()) {
			if($loginForm->isValid($this->_request->getPost())) {

				// $username = $request->getPost('username');
				// $password = $request->getPost('password');
				
				$username = $loginForm->getValue('username');
				$password = $loginForm->getValue('password');

				$authAdapter = $this->getAuthAdapter();
				
				$authAdapter->setIdentity($username);
				$authAdapter->setCredential($password);	

				$auth = Zend_Auth::getInstance();
				$result = $auth->authenticate($authAdapter);

				if($result->isValid()) {
					$this->_helper->layout->setLayout('layout');

					$identity = $authAdapter->getResultRowObject();

					$authStorage = $auth->getStorage();
					$authStorage->write($identity);

					$this->_redirect('User/home');
				} else {
					$this->_helper->layout->setLayout('signup_layout');
					$this->view->err_msg = $result;
				}
			}
			// echo "here";
			// var_dump($_POST);
			// var_dump($user->login($username,$password));
		}
		$this->_helper->layout->setLayout('signup_layout');
		$this->view->login_form = $loginForm;
		

#		var_dump($user->check_brute(4));

      
      // $register = new Application_Form_Register;
      // $request = $this->getRequest();
      // if($request->isPost()){
      //   if($register->isValid($_POST)) {
      //     /*
      //       Expected Params "firstName" , "lastName", "username", "email", "password", "confirm_password"
      //     */
      //     $params = $this->getRequest()->getPost();
      //     if($params["password"] !== $params["confirm_password"]) {
      //       // "redirect to /User/new" with an err mgs.
      //     }

      //     $this->_forward('create',null,null,array('params'=> $params));

      //   } 
      // }
      // // var_dump($this->_request->isPost());
      // $this->view->register = $register;
    	
	}

	private function getAuthAdapter(){
		// $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
		$authAdapter = new My_Auth_Adapter(Zend_Db_Table::getDefaultAdapter());
		$authAdapter->setTableName('User')
					->setIdentityColumn('username')
					->setCredentialColumn('pass');
					// ->setCredentialTreatment('UNHEX(sha512(?))');

		return $authAdapter;
	}


}

