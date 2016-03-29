<?php
/*
 |------------------------------------------------------------------
 | 插件管理
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 16/3/23 下午8:35
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace Linger\Core;

use Linger\Linger;

abstract class Pligin
{
    protected $app = null;

    protected $router = null;

    public function __construct()
    {
        $this->app = Linger::getApp();

    }

    /**
     * 开始路由前
     * @return mixed
     */
    abstract function routerStartup();

    /**
     * 路由结束
     * @return mixed
     */
    abstract function routerShutdown();

    /**
     * 分发路由前
     * @return mixed
     */
    abstract function dispatchStartup();

    /**
     * 分发路由结束
     * @return mixed
     */
    abstract function dispatchShutdown();
}