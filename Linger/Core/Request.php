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

    /**
     * 保存get提交的变量
     *
     * @var array
     */
    private $_get = [];

    /**
     * 保存post提交的变量
     *
     * @var array
     */
    private $_post = [];

    /**
     * 保存表单提交的file
     *
     * @var array
     */
    private $_file = [];

    /**
     * @var array
     */
    private $_request = [];

    /**
     * @var self
     */
    private static $ins = null;


    /**
     * Request constructor.
     */
    private function __construct()
    {
        $this->getWitchRequest();
        $this->_get = $_GET;
        $this->_post = $_POST;
        $this->_file = $_FILES;
        $this->_request = $_REQUEST;
        unset($_GET);
        unset($_POST);
        unset($_FILES);
        unset($_REQUEST);
    }

    /**
     * @return Request
     */
    public static function getInstance()
    {
        if (null === self::$ins) {
            self::$ins = new self();
        }
        return self::$ins;
    }

    /**
     * 获取请求类型,并定义常量
     */
    private function getWitchRequest()
    {
        $reqMethod = strtolower($_SERVER['REQUEST_METHOD']);
        if ('post' === $reqMethod) {
            define('IS_POST', true);
            define('IS_GET', false);
        } else {
            if ('get' === $reqMethod) {
                define('IS_POST', false);
                define('IS_GET', true);
            }
        }
    }

    /**
     * @param $type
     * @param $key
     * @param $val
     */
    public function add($type, $key, $val = '')
    {
        $arr = '_' . $type;
        if (is_array($key)) {
            $this->$arr = array_merge($this->$arr, $key);
        } else if (is_string($key)) {
            $this->$arr[$key] = $val;
        }
    }

    /**
     * @param string $key
     * @param string $callable
     *
     * @return array|mixed|null
     */
    public function get($key = '', $callable = 'htmlspecialchars')
    {
        if ('' === $key) {
            return array_map($callable, $this->_get);
        }
        if (in_array($key, $this->_get)) {
            if (is_array($this->_get[$key])) {
                return array_map($callable, $this->_get[$key]);
            }
            return $callable($this->_get[$key]);
        }

        return null;
    }

    /**
     * @param string $key
     * @param string $callable
     *
     * @return null
     */
    public function getRequest($key = '', $callable = 'htmlspecialchars')
    {
        if (empty($key)) {
            return array_map($callable, $this->_request);
        }
        if (in_array($key, $this->_request)) {
            if (is_array($this->_request[$key])) {
                return array_map($callable, $this->_request[$key]);
            }
            return $callable($this->_request[$key]);
        }
        return null;
    }

    /**
     * @param string $key
     * @param string $callable
     *
     * @return null
     */
    public function post($key = '', $callable = 'htmlspecialchars')
    {
        if ('' === $key) {
            return array_map($callable, $this->_post);
        }
        if (in_array($key, $this->_post)) {
            if (is_array($this->_post[$key])) {
                return array_map($callable, $this->_post[$key]);
            }
            return $callable($this->_post[$key]);
        }
        return null;
    }

    /**
     * 获取上传的文件信息
     *
     * @param string $key
     *
     * @return array|bool
     */
    public function file($key = '')
    {
        if ('' === $key) {
            return $this->_file;
        }
        if (in_array($key, $this->_file)) {
            return $this->_file[$key];
        } else {
            return false;
        }
    }
}