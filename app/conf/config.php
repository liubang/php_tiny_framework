<?php
/*
 |------------------------------------------------------------------
 | linger.iliubang.cn
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 16/3/23 下午8:46
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

return array(
    //debug and log config. If debug is enabled, the error message and exception will not
    //be written to the file, otherwise it will.
    'DEBUG'              => 0,
    'LOG_PATH'           => '/data/logs/scripts/linger.iliubang.cn/',
    'LOG_ARCHIVE_TYPE'   => 'Y/md/',
    //database config
    'DB_HOST'            => '127.0.0.1',
    'DB_USER'            => 'liubang',
    'DB_PWD'             => 'liubang',
    'DB_NAME'            => 'liubang',
    'DB_PREFIX'          => 'lg_',
    //...
    'DEFAULT_MODULE'     => 'home',
    'DEFAULT_CONTROLLER' => 'index',
    'DEFAULT_ACTION'     => 'index',
    'MODULE_ALLOW_LIST'  => ['index', 'admin', 'home'],
    'ROUTE'              => array(
        'home.html'        => 'home/index/index',
        'list/(\d+)\.html' => 'home/index/list/id/\1',
    ),
    'TPL_CACHE_TIME'     => 1000,
    'URL_MODEL'          => 2,
);