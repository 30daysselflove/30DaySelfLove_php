<?php
ini_set('display_errors',1);ini_set('display_startup_errors',1);error_reporting(-1);

// Default route to home
if (! isset($_REQUEST["route"]) ) {
	$_REQUEST["route"] = "www";
}

require '../application/startup.php';

$router = new lib\router();
$router->setPath(CONTROLLER_BASE_PATH);
$router->delegate();
