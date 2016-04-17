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
     * @var Config|null
     */
    private $config = null;

    /**
     * @var \Linger\Core\Request
     */
    private $request = null;

    /**
     * Router constructor.
     */
    private function __construct()
    {
        $this->config = Config::getInstance();
        $this->roules = $this->config->getConfig('ROUTE');
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
        $model = $this->config->getConfig('URL_MODEL');
        if (2 === $model) {
            $this->uri = trim(preg_replace('/^(?:index\.php|\/index\.php)?(.*?)/i', '\1', $_SERVER['REQUEST_URI']),
                '/');
            foreach ($this->roules as $key => $value) {
                if (preg_match('#' . $key . '#', $this->uri)) {
                    $this->uri = preg_replace('#' . $key . '#', $value, $this->uri);
                }
            }
            $this->uri = preg_replace('/^(.*?)(?:\.html)/i', '\1', trim($this->uri, '/'));
            if (! empty($this->uri)) {
                $req = explode('/', $this->uri);
            } else {
                $req = [];
            }
            $module = count($req) > 0 ?
                strtolower(array_shift($req)) : C('DEFAULT_MODULE');
            $controller = count($req) > 0 ?
                ucfirst(array_shift($req)) : C('DEFAULT_CONTROLLER');
            $action = count($req) > 0 ?
                lcfirst(array_shift($req)) : C('DEFAULT_ACTION');
            define('MODULE', $module);
            define('CONTROLLER', $controller . 'Controller');
            define('ACTION', $action . 'Action');
            define('CURRTMPL', $action);
            if (count($req) > 0) {
                if (count($req) % 2 !== 0) {
                    Response::getInstance()->_404();
                } else {
                    foreach ($req as $key => $val) {
                        $this->request->add('get', $key, $val);
                    }
                }
            }
        } else {
            if (1 === $model) {
                $request = Request::getInstance();
                $req = $request->get();
                $module = isset($req[C('URL_VAR_MODULE')]) ?
                    $req[C('URL_VAR_MODULE')] : C('DEFAULT_MODULE');
                $controller = isset($req[C('URL_VAR_CONTROLLER')]) ?
                    $req[C('URL_VAR_CONTROLLER')] : C('DEFAULT_CONTROLLER');
                $action = isset($req[C('URL_VAR_ACTION')]) ?
                    $req[C('URL_VAR_ACTION')] : C('DEFAULT_ACTION');
                define('MODULE', $module);
                define('CONTROLLER', $controller . 'Controller');
                define('ACTION', $action . 'Action');
                define('CURRTMPL', $action);
                unset($req[C('URL_VAR_MODULE')]);
                unset($req[C('URL_VAR_CONTROLLER')]);
                unset($req[C('URL_VAR_ACTION')]);
                $this->request->add('get', $req);
            }
        }
        return $this;
    }

    /**
     * 添加路由规则
     *
     * @param array|string $rouls
     * @param string|null  $ref
     */
    public function addRoute($rouls, $ref = null)
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
     * @param string $rouls
     * @return bool|string
     */
    public function delRoute($rouls)
    {
        if (is_string($rouls) && isset($this->roules[$rouls])) {
            $ref = $this->roules[$rouls];
            unset($this->roules[$rouls]);
            return $ref;
        }
        return false;
    }
}