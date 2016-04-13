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
        $review = Linger::M($reviewTable);

        if ('toUser' === $action) {
            $where = [
                'appraiserId' => $userId,
                'appraiserType' => 'seller',
                'isDelete' => 0
            ];
        }

        if ('fromOther' === $action) {
            $where = [
                'appraisedUserId' => $userId,
                'appraiserType' => 'buyer',
                'isDelete' => 0
            ];
        }

        $reviewInfos = $review->where($where)->limit("0, 100")->getAll();

        $result = [
            'status' => 1,
            'data' => $reviewInfos,
            'message' => ''
        ];

        echo json_encode($result);
    }
}

