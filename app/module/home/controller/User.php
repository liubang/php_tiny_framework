<?php
/*
 |------------------------------------------------------------------
 | linger.iliubang.cn
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 2016/3/28 17:24
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace home\controller;

use Linger\Core\Controller;

class UserController extends Controller
{
    public function _init()
    {
        parent::_init();
    }

    public function searchAction()
    {
        $post = $this->post();

        $userId = $post['userId'];
        $action = $post['action'];

        if ('toUser' === $action) {
            
        }

        if ('fromOther' === $action) {

        }

        $result = [
            'status' => 1,
            'data' => $post,
            'message' => ''
        ];

        echo json_encode($result);
    }
}

