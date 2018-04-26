<?php
/*	
**	 Kadimamobile.com SERVER CONFIGURATION FILE
*/


include_once("api_credentials.ini.php");

define("IN_DEV", true);
define("CURL_TIMEOUT", 4);
define("HTTP_STATUS_BASE", "HTTP/1.0"); //Standard CGI (Use "Status:" for FastCGI)
define("DB_SERVER", "localhost"); // Database Server
define("DB_NAME", "30daysselflove"); // Database name
define("DB_USER", "root");	 // Database Username
define("DB_PW", "root");	 // Database Password
define("MEDIA_BASE_PATH", "../media/");
define("COOKIE_PATH", "/");	 // Cookie Path
define("COOKIE_DOMAIN", ".30daysselflove.com");	 // Cookie domains
define("BASE_DOMAIN", "apps.30daysselflove.com");	 // Cookie domain
define('MEDIA_PATH', '/../media/image');

define("CONF_SMTP_HOST", "localhost");
define("CONF_SMTP_PORT", "25");
define("CONF_SMTP_AUTH", false);
define("CONF_SMTP_USERNAME", '');
define("CONF_SMTP_PASSWORD", '');
