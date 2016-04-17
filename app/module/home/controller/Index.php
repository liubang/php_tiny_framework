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
        echo 'hello world';
        exit;
//        $arr = [
//            ['userId' => 1034285, 'userName' => 'zhanghai'],
//            ['userId' => 201502, 'userName' => '张海']
//        ];
//
//        $this->assign('title', 'template test');
//        $this->assign('aaa', 'hello world');
//        $this->assign('arr', $arr);
//        $this->assign('flag', true);
//        $this->assign('time', time());
//        $this->display();
    }

//    /**
//     * 测试angularjs请求后端
//     */
//    public function searchAction()
//    {
//        if (IS_POST) {
//            $name = $this->post('name');
//            if ('error' !== $name && !empty($name)) {
//                $result = ['status' => 1, 'data' => ['name' => $name, 'id' => 1034285], 'message' => 'ok'];
//            } else {
//                $result = ['status' => 0, 'data' => [], 'message' => 'test error'];
//            }
//            echo json_encode($result);
//        }
//    }
}