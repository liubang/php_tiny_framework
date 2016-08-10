#!/usr/bin/env php
<?php


function parseParams($arr)
{
    $res = [];
    if (is_array($arr)) {
        foreach($arr as $v) {
            if (preg_match('/^\-\-([a-zA-Z_]+?)=(.*?)$/', $v, $info)) {
                $res[$info[1]] = $info[2];
            }
        }
    }

    return $res;
}
/*
```
     _
 ___/__) ,
(, /      __   _    _  __
  /    _(_/ (_(_/__(/_/ (_
 (_____      .-/
        )   (_/
```
*/

$dirs = [
    'app' => [
        'conf' => 'config.php',
        'library' => 'tool',
        'model' => 'IndexModel.php',
        'module' => [
            'home'  => [
                'controller' => 'IndexController.php',
                'view' => 'index.html'
            ]
        ],
        'Bootstrap.php'
    ],
    'public' => [
        'index.php',
        'js',
        'css',
        'images'
    ]
];

function initDirAndFiles($basedir, $arr)
{
    $file = $basedir;
    foreach($arr as $val) {
        $file .= '/' . $val;
        if (is_string($val)) {
            if (false !== strpos($val, '.')) {
                mkdir(realpath(dirname($file)), 0777, true);
                
            }
        }
    }
}

$config_php = <<<PHP
<?php
return array(
    'DEBUG'              => 1,
    'DB_HOST'            => '127.0.0.1',
    'DB_USER'            => '',
    'DB_PWD'             => '',
    'DB_NAME'            => '',
    'DB_PREFIX'          => '',
    //...
    'DEFAULT_MODULE'     => 'home',
    'DEFAULT_CONTROLLER' => 'index',
    'DEFAULT_ACTION'     => 'index',
    'MODULE_ALLOW_LIST'  => ['index', 'admin', 'home'],
    'ROUTE'              => array(
        'index.html'        => 'home/index/index',
    ),
    'TPL_CACHE_TIME'     => 0,
    'URL_MODEL'          => 2,
);
PHP;

$home_view_index_html = <<<PHP

PHP;

$home_controller_indexcontroller_php = <<<PHP
<?php
namespace home\controller;
use Linger\Core\Controller;

class IndexController extends Controller
{
    public function _init()
    {
        parent::_init(); 
    }
    
    public function indexAction()
    {
        \$this->display();
    }
}
PHP;

$model_indexmodel_php = <<<PHP
<?php
namespace model;

use Linger\Core\Model;

class IndexModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
}
PHP;

$bootstrap_php = <<<PHP
<?php
namespace {$params['app_name']};

class Bootstrap
{
    public function _initSession(\Linger\Core\Dispatcher \$dispatcher)
    {
        session_start();
    }
}
PHP;

$index_php = <<<PHP
<?php

define('APP_ROOT', realpath(dirname(__FILE__) . '/../'));

define('APP_NAME', '{$params['app_name']}');

require APP_ROOT . '/Linger/Linger.php';

app(APP_ROOT . '/{$params['app_name']}/conf/config.php')->bootstrap()->run();
PHP;








array_shift($argv);
if (!isset($argv[0]) || $argv[0] == '--help' || $argv[0] == '-h') {
    echo '     _', PHP_EOL;
    echo ' ___/__) ,', PHP_EOL;
    echo '(, /      __   _    _  __', PHP_EOL;
    echo '  /    _(_/ (_(_/__(/_/ (_', PHP_EOL;
    echo ' (_____      .-/', PHP_EOL;
    echo '        )   (_/', PHP_EOL, PHP_EOL;

    echo 'Usage:', PHP_EOL;
    echo '  --help, -h                for help', PHP_EOL;
    echo '  --app_name=<name>         set app name', PHP_EOL;
    echo '  --version, -v             show linger frame\'s version', PHP_EOL;
    echo PHP_EOL;
} elseif ($argv[0] == '-v' || $argv[0] == '--version') {
    echo 'v1.0.1', PHP_EOL;
    echo PHP_EOL;
} else {
    $params = parseParams($argv);
    if (isset($params['app_name']) && !empty($params['app_name'])) {
        $appPath = realpath(dirname(__FILE__) . '/../');
        if (is_writeable($appPath)) {
            mkdir($appPath . '/' . $params['app_name']);
        }
    }
}
