<?php
/*
 |------------------------------------------------------------------
 | linger.iliubang.cn
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 16/3/27 上午12:00
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace Linger\Core;

class Model
{

    protected $table = '';

    public function __construct()
    {
        $table = strtolower(str_replace('Model', '', ltrim(strrchr(get_called_class(), '\\'), '\\')));
        $this->table = $table;
    }


}