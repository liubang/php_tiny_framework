<?php
/*
 |------------------------------------------------------------------
 | linger.iliubang.cn
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 16/3/24 下午6:54
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace app;

class Bootstrap
{
    public function _initFromSite(\Linger\Core\App $app, \Linger\Core\Router $router)
    {

    }

    public function _initSession(\Linger\Core\App $app)
    {
        session_start();
    }

    //...
}