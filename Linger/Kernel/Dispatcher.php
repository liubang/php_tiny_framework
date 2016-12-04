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

namespace linger\kernel;

class Dispatcher
{
        use traits\Singleton;

        /**
         * @return traits\Singleton|null|$this
         */
        public static function singleton()
        {
                return self::getInstance();
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
                \app()->getRouter()->parseUri();

                /**
                 * call routerShutdown plugins
                 */
                $this->callPlugins('routerShutdown');

                /**
                 * call dispatchStartup plugins
                 */
                $this->callPlugins('dispatchStartup');

                if (\strpos(MODULE, '.')) {
                        \_404(SHOW_404_PAGE);
                }

                $allowModule = C('MODULE_ALLOW_LIST');

                if (!\in_array(MODULE, $allowModule)) {
                        \_403(SHOW_403_PAGE);
                }

                $class = MODULE . '\\controller\\' . CONTROLLER;

                if (!\class_exists($class)) {
                        \_404(SHOW_404_PAGE);
                }

                $controllerObj = new $class();

                if (!\is_subclass_of($controllerObj, 'linger\\Kernel\\Controller')) {
                        \_404(SHOW_404_PAGE);
                }

                if (!\method_exists($controllerObj, ACTION)) {
                        \_404(SHOW_404_PAGE);
                }

                $action = ACTION;
                $controllerObj->$action();

                /**
                 * call dispatchShutdown plugins
                 */
                $this->callPlugins('dispatchShutdown');
        }

        /**
         * @param \linger\kernel\Plugin $plugin
         */
        public function registerPlugin($plugin)
        {
                if (\is_subclass_of($plugin, '\\linger\\Kernel\\Plugin')) {
                        \app()->getRegistry()->set('plugins', $plugin, Registry::REGIST_ARR);
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
                $plugins = \app()->getRegistry()->get('plugins');

                if (!empty($plugins)) {
                        if (!empty($plugin)) {
                                if (isset($plugins[$plugin])) {
                                        $plugins[$plugin]->$level(\app()->getRequest(), \app()->getResponse());
                                }
                        } else {
                                foreach ($plugins as $plugin) {
                                        $plugin->$level(\app()->getRequest(), \app()->getResponse());
                                }
                        }
                }

                return TRUE;
        }
}
