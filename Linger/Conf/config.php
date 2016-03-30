<?php
/*
 |------------------------------------------------------------------
 | linger.iliubang.cn
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 2016/3/28 15:37
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

return [
    //---db config---//
    'DB_HOST'             => '127.0.0.1',
    'DB_USER'             => '',
    'DB_PWD'              => '',
    'DB_NAME'             => '',
    'DB_PORT'             => '3306',
    'DB_CHAR'             => 'utf8',
    'DB_PREFIX'           => '',
    'DB_SOCKET'           => '',
    'DB_PARAMS'           => [],
    'DB_DSN'              => '',

    //---system config---//
    'DEFAULT_MODULE'      => 'index',
    'DEFAULT_CONTROLLER'  => 'index',
    'DEFAULT_ACTION'      => 'index',
    'TMPL_ACTION_SUCCESS' => LINGER_ROOT . '/Tpl/jump.html',
    'TMPL_ACTION_ERROR'   => LINGER_ROOT . '/Tpl/jump.html',
    'TMPL_ACTION_404'     => LINGER_ROOT . '/Tpl/404.html',

    //---router config---//
    'ROUTE'               => [],
    'URL_HTML_SUFFIX'     => '.html',
    'PLUGIN_PATH'         => APP_ROOT . '/plugins/',
    //'VIEW_DRIVER'        => 'simple',
    'VIEW_DRIVER'         => 'linger',
    'TPL_TAG_LEFT'        => '<',
    'TPL_TAG_RIGHT'       => '>',
    'TPL_CACHE_TIME'      => -1,
    'TPL_CACHE_PATH'      => APP_ROOT . '/public/tmp/cache/',
    'TPL_COMP_PATH'       => APP_ROOT . '/public/tmp/compile/',
    'TPL_CHARSET'         => 'UTF-8',


];