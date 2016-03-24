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
    'DB_HOST'   => '127.0.0.1',
    //...
    'DEFAULT_MODULE' => 'home',
    'DEFAULT_CONTROLLER' => 'index',
    'DEFAULT_ACTION' => 'index',
    'ROUTE' => array(
        'home.html' => 'home/index/index',
        'list/(\d+)\.html' => 'home/index/list/id/\1',
    ),
);