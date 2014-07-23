<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->_helper->layout->setLayout('signup_layout');
        // action body
    }

    public function logoutAction(){
		Zend_Auth::getInstance()->clearIdentity();
		$this->_redirect(Zend_Controller_Front::getInstance()->getBaseUrl());
	}

}

