<?php
/*
 |------------------------------------------------------------------
 | linger.iliubang.cn
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 16/3/23 下午8:20
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */
error_reporting(E_ALL);
define('APP_ROOT', realpath(dirname(__FILE__) . '/../'));
define('APP_NAME', 'app');
define('ENV', 'dev');
require APP_ROOT . '/Linger/Linger.php';
//$app = Linger\Linger::app(APP_ROOT . '/app/conf/config.ini');
$app = Linger\Linger::app(APP_ROOT . '/app/conf/config.'.ENV.'.php');
$app->bootstrap()->run();