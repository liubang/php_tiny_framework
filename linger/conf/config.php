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
        'DB'                 => [
                'TEST_MASTER' => [
                        'DB_HOST'   => '127.0.0.1',
                        'DB_USER'   => '',
                        'DB_PWD'    => '',
                        'DB_NAME'   => '',
                        'DB_PORT'   => '3306',
                        'DB_CHAR'   => 'utf8',
                        'DB_PREFIX' => '',
                        'DB_SOCKET' => '',
                        'DB_PARAMS' => [],
                        'DB_DSN'    => '',
                ],
                //more database config

        ],


        //---system config---//
        'LINGER_ENVIRON'     => 'product',
        'DEBUG'              => 0,
        'LOG_PATH'           => '/data/logs/scripts/',
        'LOG_ARCHIVE_TYPE'   => 'Y/md/',
        'DEFAULT_MODULE'     => 'index',
        'DEFAULT_CONTROLLER' => 'index',
        'DEFAULT_ACTION'     => 'index',
        'MODULE_ALLOW_LIST'  => ['index'],
        'TMPL_ACTION_ERROR'  => LINGER_ROOT . '/tpl/error.html',
        'TMPL_ACTION_404'    => LINGER_ROOT . '/tpl/404.html',
        'TMPL_ACTION_403'    => LINGER_ROOT . '/tpl/403.html',
        'TMPL_ALIASES_FUNC'  => ['default' => '_default'],
        'URL_MODEL'          => 2,   //路由模式为pathinfo， 1 为$_GET传参的形式，Linger只支持这两个路由形式
        'URL_VAR_MODULE'     => 'm', //当使用get传参路由形式的时候module参数的键名
        'URL_VAR_CONTROLLER' => 'c', //当使用get传参路由形式的时候controller参数的键名
        'URL_VAR_ACTION'     => 'a', //当使用get传参路由形式的时候action参数的键名

        //---router config---//
        'ROUTE'              => [],
        'PLUGIN_PATH'        => APP_ROOT . '/plugin/',
        //'VIEW_DRIVER'        => 'simple',
        'VIEW_DRIVER'        => 'linger',
        'TPL_TAG_LEFT'       => '<',
        'TPL_TAG_RIGHT'      => '>',
        'TPL_CACHE_TIME'     => -1,
        'TPL_CACHE_PATH'     => APP_ROOT . '/public/tmp/cache/',
        'TPL_COMP_PATH'      => APP_ROOT . '/public/tmp/compile/',
        'TPL_CHARSET'        => 'UTF-8',

        //---cookie---//
        'COOKIE_NAME'        => 'LINGER_FRAME_COOKIE',
        'COOKIE_PATH'        => '/',
        'COOKIE_DOMAIN'      => NULL,
        'COOKIE_SECURE'      => FALSE,
        'COOKIE_HTTP_ONLY'   => FALSE,
];
