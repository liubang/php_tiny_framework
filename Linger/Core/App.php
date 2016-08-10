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
    private $exception = NULL;

    /**
     * @var Router
     */
    private $router = NULL;

    /**
     * @var \Linger\Core\*[]
     */
    private static $_ins = [];

    /**
     * @var \app\Bootstrap
     */
    private $bootstrap = NULL;

    /**
     * @var \Linger\Core\Request
     */
    private $request = NULL;

    /**
     * @var \Linger\Core\Response
     */
    private $response = NULL;

    /**
     * @var \Linger\Core\Dispatcher
     */
    private $dispatcher = NULL;

    /**
     * @var Registry|null
     */
    private $registry = NULL;

    /**
     * App constructor.
     *
     * @param $config
     */
    public function __construct($config)
    {
        self::$start = microtime(TRUE);
        $this->config = self::factory("Linger\\Core\\Config", $config);
        $this->exception = self::factory("Linger\\Core\\Exception");
        $this->registry = self::factory("Linger\\Core\\Registry");
        $this->request = self::factory("Linger\\Core\\Request");
        $this->router = self::factory("Linger\\Core\\Router");
        $this->dispatcher = self::factory("Linger\\Core\\Dispatcher");
        $this->response = self::factory("Linger\\Core\\Response");
    }

    /**
     * get config
     *
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * get request
     *
     * @return Request|mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * get response
     *
     * @return Response|mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * get dispatcher
     *
     * @return Dispatcher|mixed
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * get router
     *
     * @return Router|mixed
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * get registry
     *
     * @return Registry|mixed|null
     */
    public function getRegistry()
    {
        return $this->registry;
    }

    /**
     * @param string $class
     * @param array  $args
     *
     * @return mixed
     * @throws \Exception
     */
    public static function factory($class, $args = NULL)
    {
        $key = md5($class);
        if (isset(static::$_ins[$key]) && !empty(static::$_ins[$key])) {
            return static::$_ins[$key];
        }

        if (\class_exists($class)) {
            self::$_ins[$key] = new $class($args);
            return self::$_ins[$key];

        } else {
            throw new \Exception("{$class}不存在");
            exit;
        }
    }

    /**
     * 程序执行bootstrap
     * 在app的目录下如果定义了app\Bootstrap类,则会在程序分配路由前执行bootstrap中的
     * 所有_init开头的方法.
     *
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
                        call_user_func_array(
                            array($this->bootstrap, $func),
                            array($this->dispatcher)
                        );
                    }
                }
            }
        }
        return $this;
    }

    /**
     * run program
     */
    public function run()
    {
        //capture requests
        $this->request->capture();

        //init user route rules.
        $this->router->iniRoute();

        //dispatch request
        $this->dispatcher->dispatch();

        //send response to client
        $this->response->send();
    }
}