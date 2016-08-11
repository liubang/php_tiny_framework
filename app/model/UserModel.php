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

use Linger\Kernel\Model;
//use library\tool\ApiClient;

class UserModel extends Model
{
    /**
     * @var string
     */
    protected $database = 'TEST_MASTER';


    public function addUser($userInfo)
    {
        return $this->db->add($userInfo);
    }

    public function getUserInfo()
    {
        return $this->db->getAll('*');
    }
    
}