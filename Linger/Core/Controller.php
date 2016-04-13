<?php
/*
 |------------------------------------------------------------------
 | controller 基类,所有的自定义controller都要继承该类
 | 主要将一些链式调用做了简化
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 16/3/24 上午12:01
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace Linger\Core;

use Linger\Linger;

class Controller
{
    /**
     * @var \Linger\Core\View
     */
    protected $view = null;

    /**
     * @var \Linger\Core\Request
     */
    protected $request = null;

    /**
     * @var \Linger\Core\Response
     */
    protected $response = null;

    public function __construct()
    {
        if (method_exists($this, '_init')) {
            $this->_init();
        }
    }

    /**
     * init 方法
     */
    public function _init()
    {
        $this->view = new View();
        $this->request = Request::getInstance();
        $this->response = Response::getInstance();
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
    public function display($tmpl = '', $cacheTime = null, $cachePath = '', $contentType = 'text/html', $show = true)
    {
        if (empty($cacheTime)) {
            $cacheTime = Linger::C('TPL_CACHE_TIME');
        }
        if (! empty($cachePath)) {
            if (! is_dir(dirname($cachePath))) {
                $cachePath = Linger::C('TPL_CACHE_PATH') . '/' . $cachePath;
            }
        } else {
            $cachePath = rtrim(Linger::C('TPL_CACHE_PATH'),
                    '/') . '/' . MODULE . '_' . CONTROLLER . '_' . ACTION . '_' . substr(md5($_SERVER['REQUEST_URI']),
                    0, 8) . '.html';
        }
        if ('' === $tmpl) {
            $tmpl = strtolower(CURRTMPL) . '.html';
        }
        $this->view->display($tmpl, $cacheTime, $cachePath, $contentType, $show);
    }


    /**
     * @param string $tmpl
     * @param null   $cacheTime
     * @param string $cachePath
     * @param string $contentType
     */
    public function render($tmpl = '', $cacheTime = null, $cachePath = '', $contentType = 'text/html')
    {
        if ('' === $tmpl) {
            $tmpl = strtolower(CURRTMPL) . '.html';
        }
        $this->view->render($tmpl, $cacheTime, $cachePath, $contentType);
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param string $key
     * @param string $callable
     * @param null   $default
     * @return array|mixed|null
     */
    public function get($key = '', $callable = 'htmlspecialchars', $default = null)
    {
        return $this->request->get($key, $callable, $default);
    }

    /**
     * @param string $key
     * @param string $callable
     * @param null   $default
     * @return null
     */
    public function post($key = '', $callable = 'htmlspecialchars', $default = null)
    {
        return $this->request->post($key, $callable, $default);
    }

    /**
     * @param string $key
     * @param string $callable
     * @param null   $default
     * @return mixed
     */
    public function request($key = '', $callable = 'htmlspecialchars', $default = null)
    {
        return $this->request->request($key, $callable, $default);
    }

    /**
     * @param bool $code
     */
    public function _404($code = false)
    {
        $this->response->_404($code);
    }
}