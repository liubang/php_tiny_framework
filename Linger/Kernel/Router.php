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

class Router
{
        /**
         * 自定义路由规则
         *
         * @var array
         */
        private $roules = [];

        /**
         * @var string
         */
        private $uri = '';


        use traits\Singleton;

        /**
         * @return traits\Singleton|null|self
         */
        public static function singleton()
        {
                return self::getInstance();
        }


        /**
         * init route rules.
         */
        public function iniRoute()
        {
                $this->roules = \C('ROUTE');
        }

        /**
         * parse route.
         *
         * @return $this
         */
        public function parseUri()
        {
                // get the route model.
                $model = \C('URL_MODEL');

                if (2 === $model) {
                        $this->uri = \trim(\str_replace('index.php', '', $_SERVER['REQUEST_URI']), '/');
                        $pos = \strpos($this->uri, '?');
                        if (FALSE !== $pos) {
                                $this->uri = \substr($this->uri, 0, $pos);
                        }

                        if (!empty($this->roules)) {
                                foreach ($this->roules as $key => $value) {
                                        if (\preg_match('#' . $key . '#', $this->uri)) {
                                                $this->uri = \preg_replace('#' . $key . '#', $value, $this->uri);
                                        }
                                }
                        }

                        if (!empty($this->uri)) {
                                // 404
                                if (\strpos($this->uri, '.')) {
                                        \_404(SHOW_404_PAGE);
                                } else {
                                        $req = \explode('/', $this->uri);
                                }
                        } else {
                                $req = [];
                        }
                        $module = isset($req[0]) ?
                                \strtolower(\array_shift($req)) : C('DEFAULT_MODULE');
                        $controller = isset($req[0]) ?
                                \ucfirst(\array_shift($req)) : C('DEFAULT_CONTROLLER');
                        $action = isset($req[0]) ?
                                \lcfirst(\array_shift($req)) : C('DEFAULT_ACTION');
                        \define('MODULE', $module);
                        \define('CONTROLLER', \ucfirst($controller) . 'Controller');
                        \define('ACTION', $action . 'Action');
                        \define('CURRTMPL', $action);
                        if (\count($req) > 0) {
                                if (\count($req) % 2 !== 0) {
                                        \_404(SHOW_404_PAGE);
                                } else {
                                        for ($i = 0; $i < count($req); $i += 2) {
                                                \app()->getRequest()->add('get', $req[$i], $req[$i + 1]);
                                        }
                                }
                        }
                } elseif (1 === $model) {
                        $request = \app()->getRequest();
                        $req = $request->get();
                        $m = \C('URL_VAR_MODULE');
                        $c = \C('URL_VAR_CONTROLLER');
                        $a = \C('URL_VAR_ACTION');
                        $module = isset($req[$m]) ?
                                $req[$m] : \C('DEFAULT_MODULE');
                        $controller = isset($req[$c]) ?
                                $req[$c] : \C('DEFAULT_CONTROLLER');
                        $action = isset($req[$a]) ?
                                $req[$a] : \C('DEFAULT_ACTION');
                        \define('MODULE', $module);
                        \define('CONTROLLER', \ucfirst($controller) . 'Controller');
                        \define('ACTION', $action . 'Action');
                        \define('CURRTMPL', $action);
                        unset($req[$m]);
                        unset($req[$c]);
                        unset($req[$a]);
                        \app()->getRequest()->add('get', $req);
                }
                return $this;
        }

        /**
         * add a route rule.
         *
         * @param array|string $rouls
         * @param string|null  $ref
         */
        public function addRoute($rouls, $ref = NULL)
        {
                if (NULL === $ref) {
                        if (\is_array($rouls)) {
                                $this->roules = \array_merge($this->roules, $rouls);
                        }
                } else {
                        if (\is_string($rouls) && \is_string($ref)) {
                                $this->roules[$rouls] = $ref;
                        }
                }
        }

        /**
         * delete a route roule.
         *
         * @param string $rouls
         *
         * @return bool|string
         */
        public function delRoute($rouls)
        {
                if (\is_string($rouls) && isset($this->roules[$rouls])) {
                        $ref = $this->roules[$rouls];
                        unset($this->roules[$rouls]);
                        return $ref;
                }
                return FALSE;
        }
}
