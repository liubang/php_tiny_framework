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
use Linger\Linger;

class Dispatcher
{
    /**
     * @var self
     */
    private static $ins = null;

    /**
     * @var \Linger\Core\Response
     */
    private $response = null;

    private function __construct()
    {
        $this->response = Response::getInstance();
    }

    public static function getInstance()
    {
        if (null === self::$ins) {
            self::$ins = new self();
        }
        return self::$ins;
    }

    public function dispatch($uri)
    {
        $req = array();
        if (!empty($uri)) {
            $req = explode('/', $uri);
        }
        $args = array();
        $module = count($req) > 0 ? strtolower(array_shift($req)) : Linger::C('DEFAULT_MODULE');
        $controller = (count($req) > 0 ? ucfirst(array_shift($req)) : Linger::C('DEFAULT_CONTROLLER')) . 'Controller';
        $action = (count($req) > 0 ? lcfirst(array_shift($req)) : Linger::C('DEFAULT_ACTION'));
        if (!empty($req) && count($req) % 2 === 0) {
            $this->response->_404();
        }
        define('MODULE', $module);
        define('CONTROLLER', $controller);
        define('ACTION', $action . 'Action');
        define('CURRTMPL', $action);
        if (count($req) > 0) {
            for ($i = 0; $i < count($req); $i += 2) {
                $args[$req[$i]] = $req[$i + 1];
            }
        }
        $class = MODULE . '\\controller\\' . CONTROLLER;
        if (!class_exists($class)) {
            $this->response->_404();
        }
        $controllerObj = new $class();
        if (!method_exists($controllerObj, ACTION)) {
            $this->response->_404();
        }
        call_user_func_array(array($controllerObj, ACTION), array());
    }
}