<?php
/*
 |------------------------------------------------------------------
 | 请求类,处理请求相关的业务
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 16/3/24 下午7:03
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace Linger\Core;

class Request
{

    private $_get = array();

    private $_post = array();

    private static $ins = null;

    private function __construct()
    {
        $this->getWitchRequest();
    }

    public static function getInstance()
    {
        if (null === self::$ins) {
            self::$ins = new self();
        }
        return self::$ins;
    }

    private function getWitchRequest()
    {
        $reqMethod = strtolower($_SERVER['REQUEST_METHOD']);
        if ('post' === $reqMethod) {
            define('IS_POST', true);
            define('IS_GET', false);
        } else if ('get' === $reqMethod) {
            define('IS_POST', false);
            define('IS_GET', true);
        }
    }

    public function setGet($key, $value = '')
    {
        if ('' === $value) {
            $this->_get = $key;
        } else {
            $this->_get[$key] = $value;
        }
    }

    public function setPost($key, $value = '')
    {
        if ('' === $value) {
            $this->_post = $key;
        } else {
            $this->_post[$key] = $value;
        }
    }

    public function get($key = '')
    {
        if ('' === $key) {
            return $this->_get;
        }
        if (in_array($key, $this->_get)) {
            return $this->_get[$key];
        } else {
            return null;
        }
    }

    public function post($key = '')
    {
        if ('' === $key) {
            return $this->_post;
        }
        if (in_array($key, $this->_post)) {
            return $this->_post[$key];
        } else {
            return null;
        }
    }
}