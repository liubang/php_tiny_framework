<?php
/*
 |------------------------------------------------------------------
 | 相应类,处理相应相关需求
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 16/3/25 上午12:31
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace Linger\Core;

use Linger\Linger;

class Response
{
    /**
     * @var self
     */
    private static $ins = null;

    /**
     * Response constructor.
     */
    private function __construct()
    {}

    /**
     * 单例模式
     *
     * @return Response
     */
    public static function getInstance()
    {
        if (null === self::$ins) {
            self::$ins = new self();
        }
        return self::$ins;
    }

    /**
     * 返回404状态或404页面
     *
     * @param bool $code
     */
    public function _404($code = false)
    {
        if ($code) {
            header('Http/1.1 404 Not Found');
        } else {
            $view = new View();
            $view->display(Linger::C('TMPL_ACTION_404'), -1);
        }
        exit;
    }

    //TODO 403 page
    public function _403($code = false)
    {
        if ($code) {
            header('Http/1.1 403 Forbidden');
        } else {
            $view = new View();
            $view->display();
        }
    }

    /**
     * @param        $message
     * @param        $trace
     * @param string $type
     */
    public function error($message, $trace, $type = 'Exception')
    {
        if (Linger::C('DEBUG')) {
            $view = new View();
            $time = microtime(true) - App::$start;
            $view->assign('time', $time);
            $view->assign('type', $type);
            $view->assign('message', $message);
            $view->assign('trace', $trace);
            $view->display(Linger::C('TMPL_ACTION_ERROR'), -1);
            exit;
        } else {
            exit($message);
        }
    }

}