<?php
namespace MVC;

use MVC\Core\Router;

define('DS', DIRECTORY_SEPARATOR);
define('APP_PATH', realpath(dirname(__FILE__)) . DS . '..' . DS . 'Application' . DS);
require_once APP_PATH . 'Core' . DS .'AutoLoader.php';

$Router = new Router();

$Router->get(DS, function (){
    echo 'Good Home Page';
});

$Router->get(DS.'About', function (){
    echo 'Good About Page';
});
$Router->addNotFoundHandler(function (){
    echo 'Error 404';
});

$Router->run();
