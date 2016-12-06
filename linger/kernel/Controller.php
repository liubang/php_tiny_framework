<?php
/*
 |------------------------------------------------------------------
 | linger.iliubang.cn
 |------------------------------------------------------------------
 | @author    : liubang
 | @date      : 16/3/24 上午12:01
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace linger\kernel;

abstract class Controller
{
        /**
         * @var \linger\kernel\View
         */
        protected $view = NULL;

        /**
         * Controller constructor.
         */
        public function __construct()
        {
                if (\method_exists($this, '_init')) {
                        $this->_init();
                }
        }

        /**
         *  init method
         */
        protected function _init()
        {
                $this->view = new View();
        }

        /**
         * 视图变量赋值
         *
         * @param $name
         * @param $value
         */
        public function assign($name, $value)
        {
                $this->view->assign($name, $value);
        }

        /**
         * @param string $tmpl
         * @param null   $cacheTime
         * @param string $cachePath
         * @param string $contentType
         * @param bool   $show
         */
        public function display($tmpl = '', $cacheTime = NULL, $cachePath = '', $contentType = 'text/html', $show = FALSE)
        {
                if (empty($cacheTime)) {
                        $cacheTime = \C('TPL_CACHE_TIME');
                }
                if (!empty($cachePath)) {
                        if (!\is_dir(dirname($cachePath))) {
                                $cachePath = \C('TPL_CACHE_PATH') . '/' . $cachePath;
                        }
                } else {
                        $cachePath = \rtrim(\C('TPL_CACHE_PATH'),
                                        '/') . '/' . MODULE . '_' . CONTROLLER . '_' . ACTION . '_' . \substr(\md5($_SERVER['REQUEST_URI']),
                                        0, 8) . '.html';
                }

                $cacheDir = \dirname($cachePath);
                if (!\is_dir($cacheDir)) {
                        \mkdir($cacheDir, 0777, TRUE);
                }
                if ('' === $tmpl) {
                        $tmpl = \strtolower(CURRTMPL) . '.html';
                }
                $this->view->display($tmpl, $cacheTime, $cachePath, $contentType, $show);
        }


        /**
         * @param string $tmpl
         * @param null   $cacheTime
         * @param string $cachePath
         * @param string $contentType
         */
        public function render($tmpl = '', $cacheTime = NULL, $cachePath = '', $contentType = 'text/html')
        {
                if ('' === $tmpl) {
                        $tmpl = \strtolower(CURRTMPL) . '.html';
                }
                $this->view->render($tmpl, $cacheTime, $cachePath, $contentType);
        }

        /**
         * @return Request
         */
        public function getRequest()
        {
                return \app()->getRequest();
        }

        /**
         * @return Response
         */
        public function getResponse()
        {
                return \app()->getResponse();
        }

        /**
         * @param string $key
         * @param string $callable
         * @param null   $default
         *
         * @return array|mixed|null
         */
        public function get($key = '', $callable = 'htmlspecialchars', $default = NULL)
        {
                return \app()->getRequest()->get($key, $callable, $default);
        }

        /**
         * @param string $key
         * @param string $callable
         * @param null   $default
         *
         * @return null
         */
        public function post($key = '', $callable = 'htmlspecialchars', $default = NULL)
        {
                return \app()->getRequest()->post($key, $callable, $default);
        }

        /**
         * @param string $key
         * @param string $callable
         * @param null   $default
         *
         * @return mixed
         */
        public function request($key = '', $callable = 'htmlspecialchars', $default = NULL)
        {
                return \app()->getRequest()->request($key, $callable, $default);
        }

}
