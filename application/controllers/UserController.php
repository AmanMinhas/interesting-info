<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
		  $this->view->title = "INDEX";
        // action body
    }

    public function newAction()
    {
      $this->_helper->layout->setLayout('signup_layout');
      
      $register = new Application_Form_Register;
      $request = $this->getRequest();
      if($request->isPost()){
        // if($register->isValid($this->_request->isPost())) {
        if($register->isValid($_POST)) {
          echo "in here";
        } 
      }
      // var_dump($this->_request->isPost());
      $this->view->register = $register;
    }

    public function createAction()
    {
        // action body
#         $config = new Zend_Config($this->getOptions(), true);
#			$config = Zend_Registry::get('config');        
			$newUser = new Application_Model_User;
   	     $arr = array (
   	     	"username"		 => "AmanMinhas",
   	     	"first_name" 	 => "Aman",
   	     	"last_name"		 => "Minhas",
   	     	"email"			   => "amandeepSinghMinhas@gmail.com",
   	     	"pass"			   => hash('sha512',"password")
   	     );
  			$newUser->createUser($arr); 
  			
    }

    public function editAction()
    {
      $this->_helper->layout->setLayout('signup_layout');
      // action body
    }

    public function updateAction()
    {
        // action body
    }

    public function deleteAction()
    {
        // action body
    }
}











