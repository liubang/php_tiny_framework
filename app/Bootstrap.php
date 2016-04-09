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
    public function _initFromSite(\Linger\Core\Dispatcher $dispatcher)
    {

    }

    public function _initSession(\Linger\Core\Dispatcher $dispatcher)
    {
        session_start();
    }

    public function _initRegistPlugin(\Linger\Core\Dispatcher $dispatcher)
    {

    }

    public function _initPlugin(\Linger\Core\Dispatcher $dispatcher) {
        $user = new UserPlugin();
        $dispatcher->registerPlugin($user);
    }
}