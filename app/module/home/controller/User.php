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
use Linger\Linger;

class UserController extends Controller
{
    public function _init()
    {

        parent::_init();
    }

    public function searchAction()
    {
        $userId = $this->post('userId', 'intval');
        $action = $this->post('action', 'htmlspecialchars', 'toUser');

        if ($userId <= 0 || empty($action)) {
            $this->_404();
        }

        $reviewTable = 'review_' . $userId % 50;
        $review = M($reviewTable);

        if ('toUser' === $action) {
            $where = [
                'appraiserId'   => $userId,
                'appraiserType' => 'seller',
                'isDelete'      => 0,
            ];
        }

        if ('fromOther' === $action) {
            $where = [
                'appraisedUserId' => $userId,
                'appraiserType'   => 'buyer',
                'isDelete'        => 0,
            ];
        }

        $reviewInfos = $review->where($where)->limit("0, 100")->getAll();

        $result = [
            'status'  => 1,
            'data'    => $reviewInfos,
            'message' => '',
        ];

        echo json_encode($result);
    }


    public function getUserInfoAction()
    {
        echo json_encode(['status' => 1, 'data' => ['userId' => 12344, 'nickname' => '测试'], 'message' => '']);
    }

    public function testPregAction()
    {
        $str = 'php python pear';

        //$preg = '/\bp(?!h)\w+\b/is';
        //$preg = '/\bp(?=h)\w+\b/is';

//        $str = 'www.iliubang.cn';
//
//        $preg = '/(?<=www\.).*/is';
//
//        preg_match_all($preg, $str, $info, PREG_SET_ORDER);
//
//        print_r($info);
        $str = 'abbbbb';
        $preg = '/ab+?/U';

        preg_match_all($preg, $str, $info, PREG_SET_ORDER);
        print_r($info);
    }
}

