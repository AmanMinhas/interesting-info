<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	public function _initDefaultEmailTransport() {
		$emailConfig = $this->getOption('email');
		
		$smtpHost = $emailConfig['host'];
		unset($emailConfig['host']);

		$mailTransport = new Zend_Mail_Transport_Smtp($smtpHost,$emailConfig);

		Zend_Mail::setDefaultTransport($mailTransport);
		Zend_Mail::setDefaultFrom('aman.minhas16@gmail.com','Its Interesting');
	}
}

