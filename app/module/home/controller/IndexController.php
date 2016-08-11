<?php
/*
 |------------------------------------------------------------------
 | linger.iliubang.cn
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 16/3/23 下午9:02
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace home\controller;

use Linger\Kernel\Controller;

class IndexController extends Controller
{
    public function _init()
    {
        parent::_init();
    }

    public function indexAction()
    {
        $arr = [
            ['userId' => 1034285, 'userName' => 'zhanghai'],
            ['userId' => 201502, 'userName' => '张海']
        ];

//        trigger_error("这是一个错误！", E_USER_ERROR);
//        throw new \Exception('这是一个测试异常，就是要抛出去一个异常');die;


        //\Linger\Util\Log::writeLog('test.log', 'test', 3);die;
        //new \PDO('127.0.0.1', 'liubang', 'liubang');
        //$d = \D('test_master.complaints');

        $this->assign('title', 'template test');
        $this->assign('aaa', 'hello world');
        $this->assign('arr', $arr);
        $this->assign('flag', true);
        $this->assign('time', time());
        $this->getResponse()->code('500');
        $this->getResponse()->header('liubang', 'hh');
        $this->getResponse()->json($arr);
//        $this->getResponse()->json($arr, 'callback');
        $this->display();
    }
}