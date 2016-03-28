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
    private $_get = array();

    /**
     * 保存post提交的变量
     *
     * @var array
     */
    private $_post = array();

    /**
     * 保存表单提交的file
     *
     * @var array
     */
    private $_file = array();

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
        unset($_GET);
        unset($_POST);
        unset($_FILES);
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
     * 获取get传来的参数
     *
     * @param string $key
     *
     * @return array|null
     */
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

    /**
     * 获取post传递的参数
     *
     * @param string $key
     *
     * @return array|null
     */
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