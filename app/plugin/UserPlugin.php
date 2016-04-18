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

use Linger\Core\Plugin;

class UserPlugin extends Plugin
{

    public function routerStartup(\Linger\Core\Request $request, \Linger\Core\Response $response)
    {

    }

    public function routerShutdown(\Linger\Core\Request $request, \Linger\Core\Response $response)
    {
    }
    
    public function dispatchStartup(\Linger\Core\Request $request, \Linger\Core\Response $response)
    {
    }

    public function dispatchShutdown(\Linger\Core\Request $request, \Linger\Core\Response $response)
    {

    }
}