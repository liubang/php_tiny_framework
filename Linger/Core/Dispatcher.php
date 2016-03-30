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
    private static $ins = null;

    /**
     * @var \Linger\Core\Response
     */
    private $response = null;

    /**
     * Dispatcher constructor.
     */
    private function __construct()
    {
        $this->response = Response::getInstance();
    }

    /**
     * @return Dispatcher
     */
    public static function getInstance()
    {
        if (null === self::$ins) {
            self::$ins = new self();
        }
        return self::$ins;
    }

    /**
     * 分发请求
     */
    public function dispatch()
    {
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