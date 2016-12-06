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

namespace linger\kernel;


class App
{
        /**
         * record the start time.
         *
         * @var integer
         */
        public static $start;

        /**
         * @var \linger\kernel\Config|null
         */
        private $config = NULL;

        /**
         * @var \linger\kernel\Exception|null
         */
        private $exception = NULL;

        /**
         * @var \linger\kernel\Router|null
         */
        private $router = NULL;

        /**
         * @var \${APP_NAME}\Bootstrap|null
         */
        private $bootstrap = NULL;

        /**
         * @var \linger\kernel\Request|null
         */
        private $request = NULL;

        /**
         * @var \linger\kernel\Response|null
         */
        private $response = NULL;

        /**
         * @var \linger\kernel\Dispatcher|null
         */
        private $dispatcher = NULL;

        /**
         * @var \linger\kernel\Registry|null
         */
        private $registry = NULL;

        /**
         * @var \linger\kernel\App|null
         */
        private static $app = null;


        /**
         * App constructor.
         *
         */
        private function __construct()
        {
                /* record the start time */
                self::$start = \microtime(TRUE);

                /* initialize linger components */
                $this->config     = Config::getInstance();
                $this->exception  = Exception::getInstance();
                $this->registry   = Registry::getInstance();
                $this->request    = Request::getInstance();
                $this->router     = Router::getInstance();
                $this->dispatcher = Dispatcher::getInstance();
                $this->response   = Response::getInstance();
        }

        /**
         * @param string $config
         * @return App|null
         */
        public static function getInstance($config = '')
        {
                if (!self::$app instanceof self) {
                        self::$app = new self();
                }

                if (!empty($config)) {
                        self::$app->config->prepare($config);
                }

                return self::$app;
        }


        /**
         * get config
         *
         * @return mixed|Config
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
         * 程序执行bootstrap
         * 在app的目录下如果定义了app\Bootstrap类,则会在程序分配路由前执行bootstrap中的
         * 所有_init开头的方法.
         *
         * @return $this
         */
        public function bootstrap()
        {
                if (\file_exists(APP_ROOT . '/' . APP_NAME . '/Bootstrap.php')) {
                        $bootstrapClass = '\\' . APP_NAME . '\\Bootstrap';
                        if (\class_exists($bootstrapClass)) {
                                $initFuncs = \get_class_methods('\\' . APP_NAME . '\\Bootstrap');
                                $this->bootstrap = new $bootstrapClass();
                                foreach ($initFuncs as $func) {
                                        if (\substr($func, 0, 5) === '_init') {
                                                $this->bootstrap->$func($this->dispatcher);
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
                /* init configuretion */
                $this->config->loadConfig();
                /* capture requests */
                $this->request->capture();
                /* init user route rules. */
                $this->router->iniRoute();
                /* dispatch request */
                $this->dispatcher->dispatch();
                /* send response to client */
                $this->response->send();
        }
}
