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
    /**
     * @var string
     */
    protected $table = '';

    /**
     * @var array
     */
    protected $_validate = [];

    /**
     * @var array
     */
    protected $_auto = [];

    /**
     * Model constructor.
     *
     * @param string $table
     */
    public function __construct($table = '')
    {
        if (empty($table)) {
            $this->table = $table;
        } else {
            $this->table = strtolower(str_replace('Model', '', ltrim(strrchr(get_called_class(), '\\'), '\\')));
        }
    }
}