<?php

//Définitions de l'ensemble des constantes du projet
define('WEBROOT', dirname(__FILE__));
define('ROOT', dirname(WEBROOT));
define('DS', DIRECTORY_SEPARATOR);
define('APP', ROOT.DS.'App');
define('BASE_URL', $_SERVER['SERVER_NAME'].DS.'pool_php_rush');


require APP.DS.'Autoloader.php';
App\Autoloader::register(); //CHargement de l'autoloader

new \App\Dispatcher(); //On traite l'URL directement dans l'objet Dispatcher
