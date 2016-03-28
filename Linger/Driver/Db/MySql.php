<?php
/*
 |------------------------------------------------------------------
 | linger.iliubang.cn
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 16/3/24 下午7:49
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace Linger\Driver\Db;

class MySql extends DbDriver
{

    /**
     * @return string
     */
    protected function parseDsn()
    {
        $dsn = 'mysql:dbname=' . $this->config['db_name'] . ';host=' . $this->config['db_host'];
        if (!empty($this->config['db_port'])) {
            $dsn .= ';port=' . $this->config['db_port'];
        }
        if (!empty($this->config['db_socket'])) {
            $dsn .= ';unix_socket=' . $this->config['db_socket'];
        }
        if (!empty($this->config['db_char'])) {
            $this->options[\PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES ' . $this->config['db_char'];
            $dsn .= ';charset=' . $this->config['db_char'];
        }
        return $dsn;
    }

    /**
     * @return $this|bool
     */
    protected function parseTableFields()
    {
        if (empty($this->table)) {
            return false;
        }
        if (isset($this->tableFields[$this->linkId][$this->table]) && !empty($this->tableFields[$this->linkId][$this->table])) {
            $this->opt['fields'] = $this->tableFields[$this->linkId][$this->table];
            return $this;
        }
        $sql = 'SHOW COLUMNS FROM ' . $this->table;
        $result = $this->query($sql);
        if ($result) {
            $fields = '';
            foreach ($result as $val) {
                $fields .= '`' . $val['field'] . '`' . ',';
            }
            $fields = rtrim($fields, ',');
            $this->opt['fields'] = $fields;
            $this->tableFields[$this->linkId][$this->table] = $fields;
        }
        return $this;
    }

}