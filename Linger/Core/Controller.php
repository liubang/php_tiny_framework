<?php
/*
 |------------------------------------------------------------------
 | controller 基类,所有的自定义controller都要继承该类
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 16/3/24 上午12:01
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace Linger\Core;

class Controller
{

    protected $view = NULL;

    public function __construct()
    {
        if (method_exists($this, '_init')) {
            $this->_init();
        }

        $this->view = new View(APP_ROOT . '/' . APP_NAME . '/module/' . MODULE . '/view');
    }

    public function assign($name, $value)
    {
        $this->view->assign($name, $value);
    }

    public function display($tmpl = '')
    {
        if ('' === $tmpl) {
            $tmpl = strtolower(CURRTMPL) . '.html';
        }
        $this->view->display($tmpl);
    }

    public function render($tmpl = '')
    {
        if ('' === $tmpl) {
            $tmpl = strtolower(CURRTMPL) . '.html';
        }
        $this->view->render($tmpl);
    }
}