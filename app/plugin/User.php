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
use Linger\Core\Request;
use Linger\Core\Response;

class UserPlugin extends Pligin
{

    function routerStartup(Request $request, Response $response)
    {
        echo 'route start';
    }

    function routerShutdown(Request $request, Response $response)
    {
        echo 'route shutdown';
    }

    function dispatchStartup(Request $request, Response $response)
    {
        echo 'dispatch start';
    }

    function dispatchShutdown(Request $request, Response $response)
    {
        echo 'dispatch shutdown';
    }
}