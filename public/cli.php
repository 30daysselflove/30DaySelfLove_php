<?php
ini_set('display_errors',1);ini_set('display_startup_errors',1);error_reporting(-1);
chdir(dirname(__FILE__));
$route = $argv[1];

// Default route to home
if (!$route) {
    $_REQUEST["route"] = "www";
}else
{
    $_REQUEST["route"] = $route;
}

$_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__);
require '../application/startup.php';

$_SERVER['HTTP_HOST'] = BASE_DOMAIN;

$router = new lib\router();
$router->setPath(CONTROLLER_BASE_PATH);
$router->delegate();
