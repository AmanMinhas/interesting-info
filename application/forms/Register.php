<?php

class Application_Form_Register extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->setAction("test");
        $this->setMethod('POST');
        // $this->setAttrib();

        //First Name
        $firstNameElement = new Zend_Form_Element_Text('firstName');
        $firstNameElement->setLabel('First Name');
        $firstNameElement->setAttrib('class','form-group form-control col-sm-2');
        $firstNameElement->setRequired(true);

        //Last Name
        $lastNameElement = new Zend_Form_Element_Text('lastName');
        $lastNameElement->setLabel('Last Name');
        $lastNameElement->setAttrib('class','form-group form-control col-sm-2');
        $lastNameElement->setRequired(true);

        //UserName
		$usernameElement = new Zend_Form_Element_Text('username');
		$usernameElement->setLabel('Username');
		$usernameElement->setRequired(true);
		$usernameElement->setAttrib('class','form-group form-control col-sm-2');
		$usernameElement->addFilter(new Zend_Filter_StripTags());
		$usernameElement->addFilter(new Zend_Filter_HtmlEntities());
		$usernameElement->addFilter(new Zend_Filter_StringToLower());
        
        //Email Address
        $emailElement = new Zend_Form_Element_Text('email');
        $emailElement->setLabel('Email');
        $emailElement->setRequired(true);
        $emailElement->setAttrib('class','form-group form-control col-sm-2');
        $emailElement->addValidator(new Zend_Validate_EmailAddress());
        $emailElement->addFilter(new Zend_Filter_HtmlEntities());
        $emailElement->addFilter(new Zend_Filter_StripTags());

        //Password
        $passwordElement = new Zend_Form_Element_Password('password');
        $passwordElement->setLabel('Password');
        $passwordElement->setRequired(true);
        $passwordElement->setAttrib('class','form-group form-control col-sm-2');
        $passwordElement->addValidator(new Zend_Validate_StringLength(6,20));
        $passwordElement->addFilter(new Zend_Filter_HtmlEntities());
        $passwordElement->addFilter(new Zend_Filter_StripTags());

        //Confirm Password 
        $confirmPasswordElement = new Zend_Form_Element_Password('confirm_password');
        $confirmPasswordElement->setLabel('Confirm Password');
        $confirmPasswordElement->setRequired(true);
        $confirmPasswordElement->setAttrib('class','form-group form-control col-sm-2');
        $confirmPasswordElement->addValidator(new Zend_Validate_StringLength(6,20));
        $confirmPasswordElement->addFilter(new Zend_Filter_HtmlEntities());
        $confirmPasswordElement->addFilter(new Zend_Filter_StripTags());

        //Submit
        $submitButton = new Zend_Form_Element_Submit('submit');
        $submitButton->setLabel('Sign Up');
        $submitButton->setAttrib('class','btn btn-primary btn-lg btn-block btn-success');

        $this->addElements(array(
        		$firstNameElement,
        		$lastNameElement,
        		$usernameElement,
        		$emailElement,
        		$passwordElement,
        		$confirmPasswordElement,
        		$submitButton
        	));
    }


}

