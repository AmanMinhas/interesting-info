<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        // $this->view->first_name = Zend_Auth::getInstance()->getStorage()->read()->first_name;
    }

    public function indexAction()
    {
		  $this->view->title = "INDEX";
      $salt = rand(1,99);

      $var1 =  hash('sha512','password',$salt);
      $var2 =  hash('sha512','password',$salt);
      
      echo ($var1===$var2)?true:false;
        // action body
    }

    public function newAction()
    {
      $this->_helper->layout->setLayout('signup_layout');
      
      $register = new Application_Form_Register;
      $request = $this->getRequest();
      if($request->isPost()){
        if($register->isValid($_POST)) {
          /*
            Expected Params "firstName" , "lastName", "username", "email", "password", "confirm_password"
          */
          $params = $this->getRequest()->getPost();
          if($params["password"] !== $params["confirm_password"]) {
            // "redirect to /User/new" with an err mgs.
          }

          $this->_forward('create',null,null,array('params'=> $params));

        } 
      }
      // var_dump($this->_request->isPost());
      $this->view->register = $register;
    }

    public function createAction()
    {
      $params = $this->_getParam('params');
      $salt = rand(0,100);
      // var_dump($params);
      
      $newUser = new Application_Model_User;
      $arr = array (
      	"username"		 => $params["username"],
      	"first_name" 	 => $params["firstName"],
      	"last_name"		 => $params["lastName"],
      	"email"			   => $params["email"],
      	"pass"			   => hash('sha512',$params["password"],$salt),
        "salt"         => $salt,
        "role"         => "user",
        "active"       => 0
      );

      try {
  	    $id = $newUser->createUser($arr); 
        $newUser->sendActivationEmail($id,$arr["email"]);
        $this->view->message = "Please check email to activate your account. Be sure to check the spam folder as well." ;
      } catch (Zend_Exception $e) {
        var_dump($e->getMessage());
      }	
    }

    public function editAction()
    {
      $this->_helper->layout->setLayout('signup_layout');
      // action body
    }

    public function updateAction() 
    {
      // action body
      $user = new Application_Model_User;

      $id = $this->_getParam('id');
      $set = $this->_getParam('set');
      
      if($user->validUserID($id)) {
        $where = "id = $id";
        if($user->updateUser($set,$where)){
          echo "Account activated"; // Also provide link for login
        } else {
          echo "Failed to activate account, please try again";
        }
      } else {
        echo "Sorry! User does not exist";
      }
    }

    public function deleteAction()
    {
      // action body
    }

    public function activateAccountAction()
    {
      $id     = $this->getRequest()->getParam('id');
      $set    = array("active" => 1);

      $this->_forward('update',null,null,array('id'=>$id, 'set'=>$set ));
    }

    public function testAction()
    {
      $this->_helper->layout()->disableLayout();
      // $mail = new Zend_Mail();
      // $mail 
      //   ->addTo('amandeepSinghMinhas@gmail.com',"Aman Minhas")
      //   ->setSubject("My Subject")
      //   ->setBodyText("Some body msg")
      //   ->setBodyHtml("Some body msg") ;
      
      // try {
      //   $mail->send();
      // } catch (Exception $e) {
      //   var_dump($e->getMessage());
      // }
    }

    public function homeAction()
    {
        // action body
    }


}













