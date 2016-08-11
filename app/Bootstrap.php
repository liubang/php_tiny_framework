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

//use plugin\UserPlugin;

class Bootstrap
{
    public function _initSession(\Linger\Kernel\Dispatcher $dispatcher)
    {
        session_start();
    }
//
//    public function _initPlugin(\Linger\Core\Dispatcher $dispatcher)
//    {
//        $user = new UserPlugin();
//        $dispatcher->registerPlugin($user);
//    }
}