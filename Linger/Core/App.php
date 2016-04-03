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
     * @var integer
     */
    public static $start;

    /**
     * @var Exception|null
     */
    private $exception = null;

    /**
     * @var Router
     */
    private $router = null;

    /**
     * @var self
     */
    private static $ins = null;

    /**
     * @var \app\Bootstrap
     */
    private $bootstrap = null;

    /**
     * @var \Linger\Core\Request
     */
    private $request = null;

    /**
     * @var \Linger\Core\Response
     */
    private $response = null;

    /**
     * @var \Linger\Core\Dispatcher
     */
    private $dispatcher = null;

    /**
     * @var Registry|null
     */
    private $registry = null;

    /**
     * App constructor.
     *
     * @param $config
     */
    private function __construct($config)
    {
        self::$start = microtime(true);
        $this->config = Config::getInstance($config);
        $this->exception = Exception::getInstance();
        $this->registry = Registry::getInstance();
        $this->request = Request::getInstance();
        $this->router = Router::getInstance();
        $this->dispatcher = Dispatcher::getInstance();
        $this->response = Response::getInstance();
    }

    /**
     * @param array|string $config
     *
     * @return App
     */
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
            $bootstrapClass = '\\' . APP_NAME . '\\Bootstrap';
            if (class_exists($bootstrapClass)) {
                $initFuncs = get_class_methods('\\' . APP_NAME . '\\Bootstrap');
                $this->bootstrap = new $bootstrapClass();
                foreach ($initFuncs as $func) {
                    if (substr($func, 0, 5) === '_init') {
                        call_user_func_array(array($this->bootstrap, $func),
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
        $this->router->parseUri();
        $this->dispatcher->dispatch();
    }


}