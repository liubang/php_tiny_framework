<?php
/*
 |------------------------------------------------------------------
 | 处理路由
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 16/3/23 下午8:34
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace Linger\Core;

use Linger\Linger;

class Router
{
    /**
     * 自定义路由规则
     *
     * @var array
     */
    private $roules = array();

    /**
     * @var string
     */
    private $uri = '';

    /**
     * @var self
     */
    private static $ins = null;

    /**
     * @var \Linger\Core\Dispatcher
     */
    private $dispatcher = null;

    /**
     * @var \Linger\Core\Request
     */
    private $request = null;

    /**
     * @var array
     */
    private $requestArr = [];

    /**
     * Router constructor.
     */
    private function __construct()
    {
        $this->roules = Linger::C('ROUTE');
        $this->dispatcher = Dispatcher::getInstance();
        $this->request = Request::getInstance();
    }

    /**
     * @return Router
     */
    public static function getInstance()
    {
        if (null === self::$ins) {
            self::$ins = new self();
        }
        return self::$ins;
    }

    /**
     * 解析路由
     * 会优先解析在config文件中自定义的路由
     *
     * @return $this
     */
    public function parseUri()
    {
        $model = Linger::C('URL_MODEL');
        if (2 === $model) {
            $this->uri = trim(preg_replace('/^(?:index\.php|\/index\.php)?(.*?)/i', '\1', $_SERVER['REQUEST_URI']), '/');
            foreach ($this->roules as $key => $value) {
                if (preg_match('#' . $key . '#', $this->uri)) {
                    $this->uri = preg_replace('#' . $key . '#', $value, $this->uri);
                }
            }
            $this->uri = preg_replace('/^(.*?)(?:\.html)/i', '\1', trim($this->uri, '/'));
            $req = explode('/', $this->uri);
            $module = count($req) > 0 ? strtolower(array_shift($req)) : Linger::C('DEFAULT_MODULE');
            $controller = (count($req) > 0 ? ucfirst(array_shift($req)) : Linger::C('DEFAULT_CONTROLLER')) . 'Controller';
            $action = (count($req) > 0 ? lcfirst(array_shift($req)) : Linger::C('DEFAULT_ACTION'));
            define('MODULE', $module);
            define('CONTROLLER', $controller . 'Controller');
            define('ACTION', $action . 'Action');
            define('CURRTMPL', $action);
            if (count($req) > 0 ) {
                if (count($req) % 2 !== 0) {
                    Response::getInstance()->_404();
                } else {
                    foreach ($req as $key => $val) {
                        $this->request->add('get', $key, $val);
                    }
                }
            }
        } else if (1 === $model) {
            $request = Request::getInstance();
            $req= $request->get();
            $module = isset($req[Linger::C('URL_VAR_MODULE')]) ? $req[Linger::C('URL_VAR_MODULE')] : Linger::C('DEFAULT_MODULE');
            $controller = isset($req[Linger::C('URL_VAR_CONTROLLER')]) ? $req[Linger::C('URL_VAR_CONTROLLER')] : Linger::C('DEFAULT_CONTROLLER');
            $action = isset($req[Linger::C('URL_VAR_ACTION')]) ? $req[Linger::C('URL_VAR_ACTION')] : Linger::C('DEFAULT_ACTION');
            define('MODULE', $module);
            define('CONTROLLER', $controller . 'Controller');
            define('ACTION', $action . 'Action');
            define('CURRTMPL', $action);
            unset($req[Linger::C('URL_VAR_MODULE')]);
            unset($req[Linger::C('URL_VAR_CONTROLLER')]);
            unset($req[Linger::C('URL_VAR_ACTION')]);
            $this->request->add('get', $req);
        }
        return $this;
    }

    /**
     * 添加路由规则
     *
     * @param array|string $rouls
     * @param string|null  $ref
     */
    public function addRout($rouls, $ref = null)
    {
        if (null === $ref) {
            if (is_array($rouls)) {
                $this->roules = array_merge($this->roules, $rouls);
            }
        } else {
            if (is_string($rouls) && is_string($ref)) {
                $this->roules[$rouls] = $ref;
            }
        }
    }

    /**
     * 分发路由
     */
    public function dispatch()
    {
        Request::getInstance();
        $this->dispatcher->dispatch($this->requestArr);
    }
}