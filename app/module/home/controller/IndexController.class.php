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
        //echo 'this is Home module index controller index action';
        //echo strtolower(preg_replace('/Controller/', '', trim(strrchr(__CLASS__, '\\'),  '\\')));
        $this->assign('aaa', 'hello world');
        $this->display();
    }


    public function listAction()
    {
        //print_r($_GET);
        $this->assign('id', $_GET['id']);
        $this->display();
    }
}