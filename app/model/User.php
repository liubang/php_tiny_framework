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

use Linger\Core\Model;
use library\tool\ApiClient;

class UserModel extends Model
{
    public function __construct()
    {
        new ApiClient('http://xinyu.kongfz.com');
        parent::__construct();
    }
    
}