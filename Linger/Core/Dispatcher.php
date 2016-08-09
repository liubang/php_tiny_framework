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
     * @var self
     */
    private static $ins = NULL;

    /**
     * @var Router|null
     */
    private $route = NULL;

    /**
     * @var \Linger\Core\Response
     */
    private $response = NULL;

    /**
     * @var Registry|null
     */
    private $registry = NULL;

    /**
     * @var Request|null
     */
    private $request = NULL;

    /**
     * Dispatcher constructor.
     */
    public function __construct()
    {
        $this->request = App::factory('Linger\\Core\\Request');
        $this->route = App::factory('Linger\\Core\\Router');
        $this->response = App::factory('Linger\\Core\\Response');
        $this->registry = App::factory('Linger\\Core\\Registry');
    }

    /**
     * @return Dispatcher
     */
    public static function getInstance()
    {
        if (NULL === self::$ins) {
            self::$ins = new self();
        }
        return self::$ins;
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
        $this->route->parseUri();

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
            $this->registry->set('plugins', $plugin, Registry::REGIST_ARR);
        }
    }

    /**
     * run plugins
     *
     * @param      $level
     * @param null $plugin
     *
     * @return bool
     */
    private function callPlugins($level, $plugin = NULL)
    {
        $plugins = $this->registry->get('plugins');

        if (!empty($plugins)) {
            if (!empty($plugin)) {
                if (isset($plugins[$plugin])) {
                    $plugins[$plugin]->$level($this->request, $this->response);
                }
            } else {
                foreach ($plugins as $plugin) {
                    $plugin->$level($this->request, $this->response);
                }
            }
        }

        return TRUE;
    }
}