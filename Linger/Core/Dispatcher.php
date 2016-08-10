<?php
/*
 |------------------------------------------------------------------
 | linger.iliubang.cn
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 2016/3/30 12:03
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace Linger\Core;

class Dispatcher
{

    /**
     * Dispatcher constructor.
     */
    public function __construct()
    {

    }


    /**
     * 分发请求
     */
    public function dispatch()
    {
        /**
         * call routerStartup plugins
         */
        $this->callPlugins('routerStartup');

        /**
         * parse request uri and route
         */
        app()->getRouter()->parseUri();

        /**
         * call routerShutdown plugins
         */
        $this->callPlugins('routerShutdown');

        /**
         * call dispatchStartup plugins
         */
        $this->callPlugins('dispatchStartup');

        if (strpos(MODULE, '.')) {
            _404(SHOW_404_PAGE);
        }

        $class = MODULE . '\\controller\\' . CONTROLLER;

        if (!class_exists($class)) {
            _404(SHOW_404_PAGE);
        }

        $allowModule = C('MODULE_ALLOW_LIST');

        if (!in_array(MODULE, $allowModule)) {
            _403(SHOW_403_PAGE);
        }

        $controllerObj = new $class();

        if (!method_exists($controllerObj, ACTION)) {
            _404(SHOW_404_PAGE);
        }

        call_user_func_array(array($controllerObj, ACTION), array());

        /**
         * call dispatchShutdown plugins
         */
        $this->callPlugins('dispatchShutdown');
    }

    /**
     * @param \Linger\Core\Plugin $plugin
     */
    public function registerPlugin($plugin)
    {
        if (is_subclass_of($plugin, '\\Linger\\Core\\Plugin')) {
            app()->getRegistry()->set('plugins', $plugin, Registry::REGIST_ARR);
        }
    }

    /**
     * run plugins
     *
     * @param string $level
     * @param null   $plugin
     *
     * @return bool
     */
    private function callPlugins($level, $plugin = NULL)
    {
        $plugins = app()->getRegistry()->get('plugins');

        if (!empty($plugins)) {
            if (!empty($plugin)) {
                if (isset($plugins[$plugin])) {
                    $plugins[$plugin]->$level(app()->getRequest(), app()->getResponse());
                }
            } else {
                foreach ($plugins as $plugin) {
                    $plugin->$level(app()->getRequest(), app()->getResponse());
                }
            }
        }

        return TRUE;
    }
}