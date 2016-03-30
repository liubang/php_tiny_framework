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

    private function __construct()
    {

    }

    public static function getInstance()
    {
        if (null === self::$ins) {
            self::$ins = new self();
        }
        return self::$ins;
    }

    public function _404($code = false)
    {
        if ($code) {
            header('Http/1.1 404 Not Found');
        } else {
            $view = new View();
            $view->display(Linger::C('TMPL_ACTION_404'));
        }
        die();
    }

}