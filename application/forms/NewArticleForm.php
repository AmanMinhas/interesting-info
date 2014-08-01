<?php

class Application_Form_NewArticleForm extends Zend_Form
{

    public function init()
    {

        $this->setMethod('POST');
        $this->setAction(Zend_Controller_Front::getInstance()->getBaseUrl().'/User/home'); // Change Action from new to create
        // $this->setAttrib('class','login_form');

        //Title
		$titleElement = new Zend_Form_Element_Text('titleElement');
		$titleElement->setLabel('Title');
		$titleElement->setRequired(true);
		//$usernameElement->setAttrib('class','form-group form-control');
		$titleElement->addFilter(new Zend_Filter_StripTags());
		$titleElement->addFilter(new Zend_Filter_HtmlEntities());
		$titleElement->addFilter(new Zend_Filter_StringToLower());
        // $usernameElement->addValidator(new My_Validate_Text_Username());
        $this->addElement($titleElement);

        //Default Image with article
        $destination = APPLICATION_PATH."/../upload/articleImg";
        chmod($destination ,0777);

        $imgElement = new Zend_Form_Element_File('imgElement');
        $imgElement->setLabel("Upload an Image:");
        $imgElement->setDestination($destination);      
        $imgElement->addValidator('Count',false,1); //ensure only 1 file
        $imgElement->addValidator('Size',false,102400); //limit to 100K
        $imgElement->addValidator('Extension',false,'jpg,png,gif');
        $this->addElement($imgElement);

        //Description Area
        $descriptionElement = new Zend_Form_Element_Textarea('descriptionElement');
        $descriptionElement->setLabel('Description');
        $descriptionElement->setRequired(true);
        // $passwordElement->setAttrib('class','form-group form-control');
        $descriptionElement->addFilter(new Zend_Filter_HtmlEntities());
        $descriptionElement->addFilter(new Zend_Filter_StripTags());
        $this->addElement($descriptionElement);

        //Submit
        $submitButton = new Zend_Form_Element_Submit('submit');
        $submitButton->setLabel('Share');
        $submitButton->setAttrib('class','btn btn-primary btn-success');
    	$this->addElement($submitButton);
	}
}

