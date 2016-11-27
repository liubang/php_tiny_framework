<?php
/*
 |------------------------------------------------------------------
 | linger.iliubang.cn
 |------------------------------------------------------------------
 | @author    : liubang
 | @date      : 16/3/24 下午7:50
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace Linger\Driver\Db;

use Linger\Kernel\Exception;
use PDO;

abstract class DbDriver
{
        /**
         * @var \PDO[]
         */
        protected $links = [];

        /**
         * @var string
         */
        protected $linkId = NULL;

        /**
         * @var \PDOStatement
         */
        protected $PDOStatement = NULL;

        /**
         * @var int[]
         */
        protected $trans = [];

        /**
         * @var string
         */
        protected $table = '';

        /**
         * @var string
         */
        protected $sql = '';

        /**
         * @var int
         */
        protected $lastInsertId = NULL;

        /**
         * @var int
         */
        protected $affectedRows = 0;

        /**
         * @var string
         */
        protected $error = '';

        /**
         * @var array
         */
        protected $opt = [
                'fields' => '*',
                'where'  => '',
                'group'  => '',
                'order'  => '',
                'limit'  => '',
        ];

        /**
         * @var array
         */
        protected $config = [
                'db_host'   => '127.0.0.1',
                'db_user'   => '',
                'db_pwd'    => '',
                'db_name'   => '',
                'db_port'   => '3306',
                'db_char'   => 'utf8',
                'db_prefix' => '',
                'db_socket' => '',
                'db_params' => [],
                'db_dsn'    => '',
        ];

        /**
         * @var array
         */
        protected $options = [
                PDO::ATTR_CASE              => PDO::CASE_NATURAL,
                PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_ORACLE_NULLS      => PDO::NULL_NATURAL,
                PDO::ATTR_STRINGIFY_FETCHES => FALSE,
        ];

        /**
         * @var array
         */
        protected $bind = [];

        /**
         * @var string[]
         */
        protected $tableFields = [];

        /**
         * DbDriver constructor.
         *
         * @param array $config
         */
        public function __construct($config = [])
        {
                if (!empty($config)) {
                        $this->config = array_merge($this->config, $config);
                        if (is_array($this->config['db_params'])) {
                                $this->options = $this->config['db_params'] + $this->options;
                        }
                }
        }

        /**
         * @param array $config
         *
         * @return $this
         */
        public function connect($config = [])
        {
                if (!empty($config)) {
                        $this->config = array_merge($this->config, $config);
                        if (is_array($this->config['db_params'])) {
                                $this->options = $this->config['db_params'] + $this->options;
                        }
                }

                if (empty($this->config['db_dsn'])) {
                        $this->config['db_dsn'] = $this->parseDsn();
                }

                if (version_compare(PHP_VERSION, '5.3.6', '<=')) {
                        $this->options[PDO::ATTR_EMULATE_PREPARES] = FALSE;
                }

                $this->linkId = md5($this->config['db_dsn'] . serialize($this->options));

                if (isset($this->links[$this->linkId]) && $this->links[$this->linkId] instanceof PDO) {
                        return $this;
                }

                try {
                        $this->links[$this->linkId] = new PDO($this->config['db_dsn'], $this->config['db_user'],
                                $this->config['db_pwd'], $this->options);
                } catch (\PDOException $e) {
                        echo $e->getMessage();
                        print_r($e->getTrace());
                }
                return $this;
        }

        /**
         * parse dsn
         *
         * @return string
         */
        abstract protected function parseDsn();

        /**
         * parse structure of current table.
         *
         * @return self
         */
        abstract protected function parseTableFields();

        /**
         * reset query options after every sql was executed or queried,
         * avoid interference between multiple queries.
         */
        protected function resetOpt()
        {
                $this->opt = [
                        'fields' => '*',
                        'where'  => '',
                        'group'  => '',
                        'order'  => '',
                        'limit'  => '',
                ];
                if (isset($this->tableFields[$this->linkId][$this->table]) && !empty($this->tableFields[$this->linkId][$this->table])) {
                        $this->opt['fields'] = $this->tableFields[$this->linkId][$this->table];
                }
        }

        /**
         * set witch table to operate.
         *
         * @param string $table
         *
         * @return $this
         */
        public function forTable($table)
        {
                $this->table = $this->config['db_prefix'] . $table;
                $this->parseTableFields();
                return $this;
        }

        /**
         * free PDOStatement resource.
         */
        public function free()
        {
                $this->PDOStatement = NULL;
        }

        /**
         * query sql
         *
         * @param string $sql
         *
         * @return array|bool
         */
        public function query($sql)
        {
                if (!$this->linkId) {
                        return FALSE;
                }
                $this->sql = $sql;
                if (!empty($this->bind)) {
                        $this->sql = strtr($this->sql, array_map(function ($val) {
                                return '\'' . addslashes($val) . '\'';
                        }, $this->bind));
                }
                if (!empty($this->PDOStatement)) {
                        $this->free();
                }
                $this->PDOStatement = $this->links[$this->linkId]->prepare($sql);
                if (FALSE === $this->PDOStatement) {
                        Exception::error($this->PDOStatement->errorInfo(), []);
                        return FALSE;
                }
                foreach ($this->bind as $key => $value) {
                        if (is_array($value)) {
                                $this->PDOStatement->bindValue($key, $value[0], $value[1]);
                        } else {
                                $this->PDOStatement->bindValue($key, $value);
                        }
                }
                $this->bind = [];
                try {
                        $result = $this->PDOStatement->execute();
                        $this->resetOpt();
                        if (FALSE === $result) {
                                Exception::error($this->PDOStatement->errorInfo(), []);
                                return FALSE;
                        } else {
                                return $this->getResult();
                        }
                } catch (\PDOException $e) {
                        Exception::error($e->getMessage(), $e->getTrace());
                        return FALSE;
                }
        }

        /**
         * execute sql
         *
         * @param string $sql
         *
         * @return bool|int|string
         */
        public function execute($sql)
        {
                if (!$this->linkId) {
                        return FALSE;
                }
                $this->sql = $sql;
                if (!empty($this->bind)) {
                        $this->sql = strtr($this->sql, array_map(function ($val) {
                                return '\'' . addslashes($val) . '\'';
                        }, $this->bind));
                }
                if (!empty($this->PDOStatement)) {
                        $this->free();
                }
                $this->PDOStatement = $this->links[$this->linkId]->prepare($sql);
                if (FALSE === $this->PDOStatement) {
                        Exception::error($this->PDOStatement->errorInfo(), []);
                        return FALSE;
                }
                foreach ($this->bind as $key => $value) {
                        if (is_array($value)) {
                                $this->PDOStatement->bindValue($key, $value[0], $value[1]);
                        } else {
                                $this->PDOStatement->bindValue($key, $value);
                        }
                }
                $this->bind = [];
                try {
                        $result = $this->PDOStatement->execute();
                        $this->resetOpt();
                        if (FALSE === $result) {
                                Exception::error($this->PDOStatement->errorInfo(), []);
                                return FALSE;
                        } else {
                                $this->affectedRows = $this->PDOStatement->rowCount();
                                if (preg_match('/^\s*(INSERT\s+INTO|REPLACE\s+INTO)\s+/i', $sql)) {
                                        $this->lastInsertId = $this->links[$this->linkId]->lastInsertId();
                                        return $this->lastInsertId;
                                }
                                return $this->affectedRows;
                        }
                } catch (\PDOException $e) {
                        Exception::error($e->getMessage(), $e->getTrace());
                        return FALSE;
                }
        }

        /**
         * start transaction
         *
         * @return bool
         */
        public function startTrans()
        {
                if (!$this->linkId) {
                        return FALSE;
                }
                if (0 === $this->trans[$this->linkId]) {
                        $this->links[$this->linkId]->beginTransaction();
                }
                $this->trans[$this->linkId] = 1;
                return TRUE;
        }

        /**
         * commit transaction
         *
         * @return bool
         */
        public function commit()
        {
                if ($this->trans[$this->linkId] > 0) {
                        try {
                                $this->links[$this->linkId]->commit();
                        } catch (\Exception $e) {
                                Exception::error($e->getMessage(), $e->getTrace());
                        }
                        $this->trans[$this->linkId] = 0;
                }
                return TRUE;
        }

        /**
         * rollback transaction
         *
         * @return bool
         */
        public function rollback()
        {
                if ($this->trans[$this->linkId] > 0) {
                        try {
                                $this->links[$this->linkId]->rollBack();
                        } catch (\Exception $e) {
                                Exception::error($e->getMessage(), $e->getTrace());
                        }
                        $this->trans[$this->linkId] = 0;
                }
                return TRUE;
        }

        /**
         * get query set
         *
         * @return array
         */
        protected function getResult()
        {
                $result = $this->PDOStatement->fetchAll(PDO::FETCH_ASSOC);
                return $result;
        }

        /**
         * close db link.
         */
        public function close()
        {
                $this->links[$this->linkId] = NULL;
                $this->linkId = NULL;
        }

        /**
         * binding preprocessing variables.
         *
         * @param string $key
         * @param mix    $val
         */
        public function bindParam($key, $val)
        {
                $this->bind[':' . $key] = $val;
        }

        /**
         * add data to table
         *
         * @param array $data
         *
         * @return bool|int|string
         */
        public function add($data = [])
        {
                $keys = '';
                $values = '';
                if (empty($data)) {
                        if (empty($this->bind)) {
                                return FALSE;
                        }
                        $values = implode(',', array_keys($this->bind));
                        $keys = preg_replace("/:/", '', $values);
                }
                foreach ($data as $name => $val) {
                        $this->bindParam($name, $val);
                        $keys .= '`' . $name . '`' . ',';
                        $values .= ':' . $name . ',';
                }
                $sql = 'INSERT INTO '
                        . $this->table
                        . '('
                        . rtrim($keys, ',')
                        . ') VALUES ('
                        . rtrim($values, ',') . ')';
                return $this->execute($sql);
        }

        /**
         * update data from table
         *
         * @param array $data
         *
         * @return bool|int|string
         */
        public function update($data = [])
        {
                $str = '';
                if (empty($data)) {
                        if (empty($this->bind)) {
                                return FALSE;
                        }
                        $keys = array_keys($this->bind);
                        foreach ($keys as $k => $v) {
                                $str .= '`' . preg_replace('/:/', '', $v) . '`' . '=' . $v . ',';
                        }
                }

                foreach ($data as $name => $val) {
                        $this->bindParam($name, $val);
                        $str .= '`' . $name . '`' . '=:' . $name . ',';
                }
                $sql = 'UPDATE ' . $this->table . ' SET ' . rtrim($str, ',')
                        . $this->opt['where']
                        . $this->opt['limit'];
                return $this->execute($sql);
        }

        /**
         * delete data from table
         *
         * @param string $where
         *
         * @return bool|int|string
         */
        public function delete($where = '')
        {
                if (!empty($where)) {
                        $this->where($where);
                }
                $sql = 'DELETE FROM '
                        . $this->table
                        . $this->opt['where']
                        . $this->opt['limit'];
                return $this->execute($sql);
        }

        /**
         * get a row of data
         *
         * @param string $field
         * @param string $where
         *
         * @return array|bool|mixed
         */
        public function getRow($field = '', $where = '')
        {
                if (!empty($field)) {
                        $this->fields($field);
                }
                if (!empty($where)) {
                        $this->where($where);
                }
                $this->opt['limit'] = ' LIMIT 1 ';
                $sql = 'SELECT '
                        . $this->opt['fields']
                        . ' FROM `'
                        . $this->table . '`'
                        . $this->opt['where']
                        . $this->opt['limit'];

                $result = $this->query($sql);

                if (FALSE !== $result) {
                        if (empty($result)) {
                                return [];
                        } else {
                                return $result[0];
                        }
                }
                return FALSE;
        }

        /**
         * get a field of data
         *
         * @param string $field
         * @param string $where
         *
         * @return array|bool
         */
        public function getOne($field = '', $where = '')
        {
                if (!empty($field)) {
                        $this->fields($field);
                }
                if (!empty($where)) {
                        $this->where($where);
                }
                if (stripos($this->opt['fields'], ',')) {
                        Exception::error('This method can only query a field, but a number of fields are presented.',
                                []);
                        return FALSE;
                }
                $this->opt['limit'] = ' LIMIT 1 ';
                $sql = 'SELECT ' . $this->opt['fields'] . ' FROM ' . $this->table
                        . $this->opt['where']
                        . $this->opt['group']
                        . $this->opt['order']
                        . $this->opt['limit'];
                $result = $this->query($sql);
                if (FALSE !== $result) {
                        if (empty($result)) {
                                return [];
                        } else {
                                return $result[0][$this->opt['fields']];
                        }
                }
                return FALSE;
        }

        /**
         * get all data that meets the conditions.
         *
         * @param string $field
         * @param string $where
         * @param string $limit
         *
         * @return array|bool
         */
        public function getAll($field = '', $where = '', $limit = '')
        {
                if (!empty($field)) {
                        $this->fields($field);
                }
                if (!empty($where)) {
                        $this->where($where);
                }
                if (!empty($limit)) {
                        $this->limit($limit);
                }
                $sql = 'SELECT ' . $this->opt['fields'] . ' FROM ' . $this->table
                        . $this->opt['where']
                        . $this->opt['group']
                        . $this->opt['order']
                        . $this->opt['limit'];
                return $this->query($sql);
        }

        /**
         * set query fields
         *
         * @param $arr
         *
         * @return $this
         */
        public function fields($arr)
        {
                $field = '';
                if (is_array($arr)) {
                        foreach ($arr as $val) {
                                if (is_array($val)) {
                                        $field .= '`' . key($val) . '`' . ' AS \'' . current($val) . '\',';
                                } else {
                                        $field .= '`' . $val . '`' . ',';
                                }
                        }
                        $field = rtrim($field, ',');
                }
                if (is_string($arr)) {
                        $field = $arr;
                }
                $this->opt['fields'] = $field;
                return $this;
        }

        /**
         * set limit condition
         *
         * @param $limit
         *
         * @return $this
         */
        public function limit($limit)
        {
                if (is_array($limit)) {
                        $limit = implode(',', $limit);
                }

                $this->opt['limit'] = ' LIMIT ' . $limit;
                return $this;
        }

        /**
         * set order condition
         *
         * @param $order
         *
         * @return $this
         */
        public function order($order)
        {
                $str = ' ORDER BY ';
                if (is_string($order)) {
                        $order = $str . $order;
                }

                if (is_array($order)) {
                        foreach ($order as $key => $val) {
                                $str .= '`' . $key . '` ' . $val . ',';
                        }
                        $order = rtrim($str, ',');
                }
                $this->opt['order'] = $order;
                return $this;
        }

        /**
         * set group by condition
         *
         * @param $group
         *
         * @return $this
         */
        public function group($group)
        {
                if (is_array($group)) {
                        $group = ' `' . implode('`,', $group) . '`';
                }

                $this->opt['group'] = 'GROUP BY ' . $group;

                return $this;
        }

        /**
         * set where condition
         *
         * @param $where
         *
         * @return $this
         */
        public function where($where)
        {
                $tmp = [];
                if (is_array($where)) {
                        foreach ($where as $key => $val) {
                                if (is_array($val)) {
                                        $tmp[] = $val['k'] . $val['s'] . ':' . $val['k'];
                                        $this->bindParam($val['k'], $val['v']);
                                } else {
                                        $tmp[] = $key . '=:' . $key;
                                        $this->bindParam($key, $val);
                                }
                        }
                        $where = ' WHERE ' . implode(' AND ', $tmp);
                }
                if (is_string($where)) {
                        $this->opt['where'] = $where;
                }
                return $this;
        }

        /**
         * get current linked  PDO resource
         *
         * @return null|PDO
         */
        public function getDb()
        {
                if (!$this->linkId) {
                        return $this->links[$this->linkId];
                }
                return NULL;
        }

        /**
         * print debug info
         */
        public function debug()
        {
                echo '当前操作的表为：' . $this->table . '<br />';
                echo '执行语句为：' . $this->sql . '<br />';
        }


        public function __destruct()
        {
                if (!empty($this->PDOStatement)) {
                        $this->free();
                }
                $this->close();
        }
}
