<?php

class Application_Form_LoginForm extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->SetName('Login');
        $this->setMethod('POST');
        $this->setAction(Zend_Controller_Front::getInstance()->getBaseUrl().'/Login/auth');
        $this->setAttrib('class','login_form');

        //UserName
		$usernameElement = new Zend_Form_Element_Text('username');
		$usernameElement->setLabel('Username');
		$usernameElement->setRequired(true);
		//$usernameElement->setAttrib('class','form-group form-control');
		$usernameElement->addFilter(new Zend_Filter_StripTags());
		$usernameElement->addFilter(new Zend_Filter_HtmlEntities());
		$usernameElement->addFilter(new Zend_Filter_StringToLower());
        // $usernameElement->addValidator(new My_Validate_Text_Username());
        $this->addElement($usernameElement);

        //Password
        $passwordElement = new Zend_Form_Element_Password('password');
        $passwordElement->setLabel('Password');
        $passwordElement->setRequired(true);
        // $passwordElement->setAttrib('class','form-group form-control');
        $passwordElement->addFilter(new Zend_Filter_HtmlEntities());
        $passwordElement->addFilter(new Zend_Filter_StripTags());
        $this->addElement($passwordElement);

        //Submit
        $submitButton = new Zend_Form_Element_Submit('submit');
        $submitButton->setLabel('Log In');
        $submitButton->setAttrib('class','btn btn-primary btn-success');
    	$this->addElement($submitButton);

    	// $this->getElement('username')->addDecorator('Label',array('placement'=>'APPEND'));
    	// $this->getElement('username')->addDecorator('HtmlTag',array('tag'=>'dl','class'=>'login_form'));
    	// $this->getElement('password')->addDecorator('Label',array('placement'=>'APPEND'));
    	// $this->getElement('password')->addDecorator('HtmlTag',array('tag'=>'dl','class'=>'login_form'));
    	// $this->getElement('submit')->addDecorator('Label',array('placement'=>'APPEND'));
    	// $this->getElement('submit')->addDecorator('HtmlTag',array('tag'=>'dl','class'=>'login_form'));

    	// $this->addDisplayGroup(array("username","password","submit"),'submitButtons',array(
    	// 		'decorators'=>array(
    	// 			'FormElements',
    	// 			array('HtmlTag',array('tag'=>'div'), 'class' => 'form-buttons')
    	// 		)
    	// 	)
    	// );

  //   	$this->addDisplayGroup(array('submit', 'delete'), 'submitButtons', array(
		//      'decorators' => array(
		//          'FormElements',
		//          array('HtmlTag', array('tag' => 'div', 'class' => 'form-buttons')),
		//      ),
		// ));
    }

}