<?php

error_reporting(E_ALL | E_STRICT);    

set_include_path(get_include_path() . ":" . $_SERVER['DOCUMENT_ROOT'] . "/../lib");

require_once($_SERVER['DOCUMENT_ROOT'] . "/../application/app_config.ini.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/../application/server_config.ini.php");



date_default_timezone_set("America/Los_Angeles");

include($_SERVER['DOCUMENT_ROOT'] . "/../lib/autoloader.php");

$autoloader = new autoloader();



