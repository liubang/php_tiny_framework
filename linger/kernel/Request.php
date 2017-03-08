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

namespace linger\kernel;

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
        private $_content = NULL;

        /**
         * @var null|self
         */
        private static $instance = null;

        /**
         * Request constructor.
         */
        private function __construct()
        {
        }

        /**
         * @return Request|null
         */
        public static function getInstance()
        {
                if (!self::$instance instanceof self) {
                        self::$instance = new self();
                }

                return self::$instance;
        }


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
                        \define('IS_CLI', TRUE);
                        return;
                }

                $reqMethod = \strtolower($_SERVER['REQUEST_METHOD']);

                if ('post' === $reqMethod) {
                        \define('IS_POST', TRUE);
                        \define('IS_GET', FALSE);
                } else {
                        if ('get' === $reqMethod) {
                                \define('IS_POST', FALSE);
                                \define('IS_GET', TRUE);
                        }
                }

                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'xmlhttprequest' == \strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                        \define('IS_AJAX', TRUE);
                } else {
                        \define('IS_AJAX', FALSE);
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
         *
         * @return array|mixed|null
         */
        public function get($key = '', $callable = 'htmlspecialchars', $default = NULL)
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
                if (NULL !== $default) {
                        return $default;
                }
                return NULL;
        }

        /**
         * @param string $key
         * @param string $callable
         * @param mixed  $default
         *
         * @return null
         */
        public function request($key = '', $callable = 'htmlspecialchars', $default = NULL)
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
                if (NULL !== $default) {
                        return $default;
                }
                return NULL;
        }

        /**
         * @param string $key
         * @param string $callable
         * @param mixed  $default
         *
         * @return null
         */
        public function post($key = '', $callable = 'htmlspecialchars', $default = NULL)
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
                if (NULL !== $default) {
                        return $default;
                }
                return NULL;
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
                if (\key_exists($key, $this->_file)) {
                        return $this->_file[$key];
                } else {
                        return FALSE;
                }
        }

        /**
         * get the request body content.
         *
         * @param bool $asResource if true, a resource will be returned
         *
         * @return resource|string the request body content or a resource to read the body stream.
         * @throws \Exception
         */
        public function content($asResource = FALSE)
        {
                if (FALSE === $this->_content || (TRUE === $asResource && NULL !== $this->_content)) {
                        throw new \Exception('content() can only be called once when using the resource return type.');
                }

                if (TRUE === $asResource) {
                        $this->_content = FALSE;

                        return \fopen('php://input', 'rb');
                }

                if (NULL === $this->_content) {
                        $this->_content = \file_get_contents('php://input');
                }

                return $this->_content;
        }
}
