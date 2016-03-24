<?php
/*
 |------------------------------------------------------------------
 | linger.iliubang.cn
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 16/3/25 上午12:56
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace model;

class UserModel
{
    public function __construct()
    {

    }

    public function getUserInfoById($userId)
    {
        return array(
            'userId' => $userId,
            'nickname' => 'liubang'
        );
    }
}