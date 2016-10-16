<?php
/*
 |------------------------------------------------------------------
 | linger.iliubang.cn
 |------------------------------------------------------------------
 | @author    : liubang
 | @date      : 16/3/24 下午7:03
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace Linger\Kernel;

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
         * @var string|null
         */
        private $_content = null;
        
        /**
         * capture the custom request
         */
        public function capture()
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
         * 获取请求类型,并定义常量
         */
        private function getWitchRequest()
        {
                if ('cli' === \php_sapi_name()) {
                        \define('IS_CLI', true);
                        return;
                }
                
                $reqMethod = \strtolower($_SERVER['REQUEST_METHOD']);
                
                if ('post' === $reqMethod) {
                        \define('IS_POST', true);
                        \define('IS_GET', false);
                } else {
                        if ('get' === $reqMethod) {
                                \define('IS_POST', false);
                                \define('IS_GET', true);
                        }
                }
                
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'xmlhttprequest' == \strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                        \define('IS_AJAX', true);
                } else {
                        \define('IS_AJAX', false);
                }
        }
        
        /**
         * @param string $type
         * @param string $key
         * @param string $val
         */
        public function add($type, $key, $val = '')
        {
                if (\is_array($key)) {
                        if ('post' === $type) {
                                $this->_post = \array_merge($this->_post, $key);
                        } elseif ('get' === $type) {
                                $this->_get = \array_merge($this->_get, $key);
                        }
                } elseif (\is_string($key)) {
                        if ('post' === $type) {
                                $this->_post[$key] = $val;
                        } elseif ('get' === $type) {
                                $this->_get[$key] = $val;
                        }
                }
        }
        
        /**
         * @param string     $key
         * @param string     $callable
         * @param null|mixed $default
         * @return array|mixed|null
         */
        public function get($key = '', $callable = 'htmlspecialchars', $default = null)
        {
                if ('' === $key) {
                        return \array_map($callable, $this->_get);
                }
                if (\key_exists($key, $this->_get)) {
                        if (\is_array($this->_get[$key])) {
                                return \array_map($callable, $this->_get[$key]);
                        }
                        return $callable($this->_get[$key]);
                }
                if (null !== $default) {
                        return $default;
                }
                return null;
        }
        
        /**
         * @param string $key
         * @param string $callable
         * @param mixed  $default
         * @return null
         */
        public function request($key = '', $callable = 'htmlspecialchars', $default = null)
        {
                if (empty($key)) {
                        return \array_map($callable, $this->_request);
                }
                if (\key_exists($key, $this->_request)) {
                        if (\is_array($this->_request[$key])) {
                                return \array_map($callable, $this->_request[$key]);
                        }
                        return $callable($this->_request[$key]);
                }
                if (null !== $default) {
                        return $default;
                }
                return null;
        }
        
        /**
         * @param string $key
         * @param string $callable
         * @param mixed  $default
         * @return null
         */
        public function post($key = '', $callable = 'htmlspecialchars', $default = null)
        {
                if ('' === $key) {
                        return \array_map($callable, $this->_post);
                }
                if (\key_exists($key, $this->_post)) {
                        if (\is_array($this->_post[$key])) {
                                return \array_map($callable, $this->_post[$key]);
                        }
                        return $callable($this->_post[$key]);
                }
                if (null !== $default) {
                        return $default;
                }
                return null;
        }
        
        /**
         * 获取上传的文件信息
         *
         * @param string $key
         * @return array|bool
         */
        public function file($key = '')
        {
                if ('' === $key) {
                        return $this->_file;
                }
                if (\in_array($key, $this->_file)) {
                        return $this->_file[$key];
                } else {
                        return false;
                }
        }
        
        /**
         * get the request body content.
         *
         * @param bool $asResource if true, a resource will be returned
         * @return resource|string the request body content or a resource to read the body stream.
         * @throws \Exception
         */
        public function content($asResource = false)
        {
                if (false === $this->_content || (true === $asResource && null !== $this->_content)) {
                        throw new \Exception('content() can only be called once when using the resource return type.');
                }
                
                if (true === $asResource) {
                        $this->_content = false;
                        
                        return \fopen('php://input', 'rb');
                }
                
                if (null === $this->_content) {
                        $this->_content = \file_get_contents('php://input');
                }
                
                return $this->_content;
        }
}
