<?php
define('WWWROOT', dirname(__FILE__)); 								#Define access to the www folder path.
define('APPROOT', dirname(WWWROOT));  								#Define access to the app folder path.
define('ROOT', dirname(APPROOT));	  								#Define access to the main folder path.
define('SEPARATOR', DIRECTORY_SEPARATOR);							#Define directory separator for create path.
define('CACHE', APPROOT.SEPARATOR.'cache');							#Define access to cache folder.
define('LIB', ROOT.SEPARATOR.'lib');								#Define access to lib folder.
define('CORE', LIB.SEPARATOR.'core');								#Define access to system core folder.
define('URL', dirname(dirname(dirname($_SERVER['SCRIPT_NAME']))) == '/' ? '' : dirname(dirname(dirname($_SERVER['SCRIPT_NAME']))));	
if($_SERVER['SERVER_ADDR'] == '127.0.0.1')
	define('MODE', 'development');									#Define the project mode. (development or production)
else
	define('MODE', 'production');

ob_start();
$startedAt = microtime(true);
require LIB.SEPARATOR.'init.php';
$endedAt = microtime(true);
define('GENERATION_TIME', round($endedAt-$startedAt, 7));			#Define the page generation time.

ob_end_flush();
?>