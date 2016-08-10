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
use Linger\Core\Controller;

class IndexController extends Controller
{

    public function indexAction()
    {
//        echo 'hello world';
//        exit;
        $arr = [
            ['userId' => 1034285, 'userName' => 'zhanghai'],
            ['userId' => 201502, 'userName' => '张海']
        ];

        $this->assign('title', 'template test');
        $this->assign('aaa', 'hello world');
        $this->assign('arr', $arr);
        $this->assign('flag', true);
        $this->assign('time', time());
        $this->display();
    }
}