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

namespace Linger\Kernel;

abstract class Model
{
    /**
     * @var string
     */
    protected $database = '';

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
     * @var \Linger\Driver\Db\DbDriver|null
     */
    protected $db = null;

    /**
     * Model constructor.
     *
     * @param string $table
     * @param string $database
     */
    public function __construct($table = '', $database = '')
    {
        if (!empty($table)) {
            $this->table = $table;
        } else {
            $this->table = strtolower(str_replace('Model', '', ltrim(strrchr(get_called_class(), '\\'), '\\')));
        }

        if (!empty($database)) {
            $this->database = $database;
        }

        $this->db = \D($this->database . '.' . $this->table);
    }
}