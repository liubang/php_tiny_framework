<?php
/*
 |------------------------------------------------------------------
 | linger.iliubang.cn
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 16/3/23 下午8:34
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace Linger\Core;

class App
{
    /**
     * @var Router
     */
    private $router = null;

    /**
     * @var self
     */
    private static $ins = null;

    private function __construct($config)
    {
        Config::configInit($config);
        $this->router = new Router();
    }

    public static function getInstance($config)
    {
        if (null === self::$ins) {
            self::$ins = new self($config);
        }
        return self::$ins;
    }

    /**
     * 程序执行bootstrap
     *
     * 在app的目录下如果定义了app\Bootstrap类,则会在程序分配路由前执行bootstrap中的
     * 所有_init开头的方法.
     * @return $this
     */
    public function bootstrap()
    {
        if (file_exists(APP_ROOT . '/' . APP_NAME . '/Bootstrap.php')) {
            if (class_exists('\\' . APP_NAME . '\\Bootstrap')) {
                $initFuncs = get_class_methods('\\' . APP_NAME . '\\Bootstrap');
                foreach ($initFuncs as $func) {
                    if (substr($func, 0, 5) === '_init') {
                        call_user_func_array(array('\\' . APP_NAME . '\\Bootstrap', $func),
                            array('app' => $this, 'router' => $this->router));
                    }
                }
            }
        }
        return $this;
    }

    /**
     * 运行程序
     */
    public function run()
    {
        $this->router->parseUri()->dispatch();
    }


}