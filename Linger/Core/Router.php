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

use Linger\Linger;

class Router
{
    private $roules = array();
    private $uri = '';

    public function __construct()
    {
        $this->roules = Linger::C('ROUTE');
    }

    public function parseUri()
    {
        $this->uri = trim(str_replace('index.php', '', $_SERVER['REQUEST_URI']), '/');
        foreach ($this->roules as $key => $value) {
            if (preg_match('#' . $key . '#', $this->uri)) {
                $this->uri = preg_replace('#' . $key . '#', $value, $this->uri);
            }
        }
        return $this;
    }

    public function dispatch()
    {
        if (count($this->uri) % 2 === 0) {
            header("HTTP/1.1 404 Not Found");
            die();
        }
        $req = explode('/', $this->uri);
        $args = array();
        $module = count($req) > 0 ? strtolower(array_shift($req)) : Linger::C('DEFAULT_MODULE');
        $controller = (count($req) > 0 ? ucfirst(array_shift($req)) : Linger::C('DEFAULT_CONTROLLER')) . 'Controller';
        $action = (count($req) > 0 ? lcfirst(array_shift($req)) : Linger::C('DEFAULT_ACTION'));
        define('MODULE', $module);
        define('CONTROLLER', $controller);
        define('ACTION', $action . 'Action');
        define('CURRTMPL', $action);
        if (count($req) > 0) {
            for($i = 0; $i < count($req); $i += 2) {
                $args[$req[$i]] = $req[$i+1];
            }
        }
        $class =  MODULE . '\\controller\\' . CONTROLLER;
        $controllerObj = new $class();
        $_GET = $args;
        call_user_func_array(array($controllerObj, ACTION), array());
    }
}