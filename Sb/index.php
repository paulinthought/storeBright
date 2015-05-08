<?php

namespace Sb;

$loadtime = time();
/**
 * Dispatcher
 *
 * PHP >= 5.3
 *
 * @copyright     Copyright 2013-2014, StoreBright Software. (http://StoreBright.com)
 * @link          http://StoreBright.com/php
 * @since         February 2013
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
session_start();

ini_set('display_errors',1);
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
/**
 *  Define OS directory seperator
 */
if (!isset($_SERVER['OS']) || strripos($_SERVER['OS'], 'windows') === false) {
    define ('DS', '/');
} else
{
    define ('DS', '\\'); 
}
	
/**
* Define absolute path to shops sys folder
*/
define('SYS_PATH', '/var/www/sb/Sys/');
define('ADMIN_PATH', '/var/www/sb/Sb/Admin/');
define('STORE_PATH', '/var/www/sb/Sb/Store/');

/**
 * 
 */
require_once SYS_PATH . 'Initializer.php';
//require_once ADMIN_PATH . DS . 'Sb.php';


$Sb = \Sys\Initializer::init();
             

// no output above this line please 
echo '<pre>';
//print_r($Sb);  
echo '</pre>';     


echo 'FILES<br>';
var_dump(get_included_files());
echo memory_get_peak_usage ( true ) . ' bytes<br>';
echo 'load time '. time() - $loadtime . ' ms';
