<?php
/*
 |------------------------------------------------------------------
 | linger.iliubang.cn
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 16/4/10 上午1:59
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace plugin;

use Linger\Core\Pligin;

class UserPlugin extends Pligin
{

    public function __construct()
    {
        echo 111;die;
    }

    function routerStartup(\Linger\Core\Request $request, \Linger\Core\Response $response)
    {
        echo 'route start';
    }

    function routerShutdown(\Linger\Core\Request $request, \Linger\Core\Response $response)
    {
        echo 'route shutdown';
    }

    function dispatchStartup(\Linger\Core\Request $request, \Linger\Core\Response $response)
    {
        echo 'dispatch start';
    }

    function dispatchShutdown(\Linger\Core\Request $request, \Linger\Core\Response $response)
    {
        echo 'dispatch shutdown';
    }
}