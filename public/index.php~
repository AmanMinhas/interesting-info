<?php
error_reporting(E_ALL);
ini_set('display_errors', true);

echo "<b>This is Zend Home Page located in /public/index.php</b><br>";

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

echo "APPLICATION_PATH : ".APPLICATION_PATH."<br>";

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

echo "APPLICATION_ENV : ".APPLICATION_ENV."<br>";

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH . '/../../library'),
    get_include_path(),
)));

echo "Paths : ".get_include_path()."<br>"; 

/** Zend_Application */
echo require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();
