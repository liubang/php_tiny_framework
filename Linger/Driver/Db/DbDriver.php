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
    protected $linkId = null;

    /**
     * @var \PDOStatement
     */
    protected $PDOStatement = null;

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
    protected $lastInsertId = null;

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
        PDO::ATTR_CASE              => PDO::CASE_LOWER,
        PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_ORACLE_NULLS      => PDO::NULL_NATURAL,
        PDO::ATTR_STRINGIFY_FETCHES => false,
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
        if (! empty($config)) {
            $this->config = array_merge($this->config, $config);
            if (is_array($this->config['db_params'])) {
                $this->options = $this->config['db_params'] + $this->options;
            }
        }
    }

    /**
     * @param array $config
     * @return $this
     */
    public function connect($config = [])
    {
        if (! empty($config)) {
            $this->config = array_merge($this->config, $config);
            if (is_array($this->config['db_params'])) {
                $this->options = $this->config['db_params'] + $this->options;
            }
        }

        if (empty($this->config['db_dsn'])) {
            $this->config['db_dsn'] = $this->parseDsn();
        }

        if (version_compare(PHP_VERSION, '5.3.6', '<=')) {
            $this->options[PDO::ATTR_EMULATE_PREPARES] = false;
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
     * 解析PDO连接用的dsn
     *
     * @return string
     */
    abstract protected function parseDsn();

    /**
     * 解析当前表的表结构
     *
     * @return self
     */
    abstract protected function parseTableFields();

    /**
     * 每次执行完sql后，重置查询选项，避免单例模式下多次查询之间的干扰
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
        if (isset($this->tableFields[$this->linkId][$this->table]) && ! empty($this->tableFields[$this->linkId][$this->table])) {
            $this->opt['fields'] = $this->tableFields[$this->linkId][$this->table];
        }
    }

    /**
     * 设置当前要操作的表
     *
     * @param string $table
     * @return $this
     */
    public function forTable($table)
    {
        $this->table = $this->config['db_prefix'] . $table;
        $this->parseTableFields();
        return $this;
    }

    /**
     * 释放资源
     */
    public function free()
    {
        $this->PDOStatement = null;
    }

    /**
     * 执行查询sql
     *
     * @param string $sql
     * @return array|bool
     */
    public function query($sql)
    {
        if (! $this->linkId) {
            return false;
        }
        $this->sql = $sql;
        if (! empty($this->bind)) {
            $this->sql = strtr($this->sql, array_map(function ($val) {
                return '\'' . addslashes($val) . '\'';
            }, $this->bind));
        }
        if (! empty($this->PDOStatement)) {
            $this->free();
        }
        $this->PDOStatement = $this->links[$this->linkId]->prepare($sql);
        if (false === $this->PDOStatement) {
            error($this->PDOStatement->errorInfo(),[]);
            return false;
        }
        foreach ($this->bind as $key => $value) {
            if (is_array($value)) {
                $this->PDOStatement->bindValue($key, $value[0], $value[1]);
            } else {
                $this->PDOStatement->bindValue($key, $value);
            }
        }
        $this->bind = array();
        try {
            $result = $this->PDOStatement->execute();
            $this->resetOpt();
            if (false === $result) {
                error($this->PDOStatement->errorInfo(),[]);
                return false;
            } else {
                return $this->getResult();
            }
        } catch (\PDOException $e) {
            error($e->getMessage(), $e->getTrace());
            return false;
        }
    }

    /**
     * 执行插入，修改，删除sql
     *
     * @param string $sql
     * @return bool|int|string
     */
    public function execute($sql)
    {
        if (! $this->linkId) {
            return false;
        }
        $this->sql = $sql;
        if (! empty($this->bind)) {
            $this->sql = strtr($this->sql, array_map(function ($val) {
                return '\'' . addslashes($val) . '\'';
            }, $this->bind));
        }
        if (! empty($this->PDOStatement)) {
            $this->free();
        }
        $this->PDOStatement = $this->links[$this->linkId]->prepare($sql);
        if (false === $this->PDOStatement) {
            error($this->PDOStatement->errorInfo(),[]);
            return false;
        }
        foreach ($this->bind as $key => $value) {
            if (is_array($value)) {
                $this->PDOStatement->bindValue($key, $value[0], $value[1]);
            } else {
                $this->PDOStatement->bindValue($key, $value);
            }
        }
        $this->bind = array();
        try {
            $result = $this->PDOStatement->execute();
            $this->resetOpt();
            if (false === $result) {
                error($this->PDOStatement->errorInfo(),[]);
                return false;
            } else {
                $this->affectedRows = $this->PDOStatement->rowCount();
                if (preg_match('/^\s*(INSERT\s+INTO|REPLACE\s+INTO)\s+/i', $sql)) {
                    $this->lastInsertId = $this->links[$this->linkId]->lastInsertId();
                    return $this->lastInsertId;
                }
                return $this->affectedRows;
            }
        } catch (\PDOException $e) {
            error($e->getMessage(), $e->getTrace());
            return false;
        }
    }

    /**
     * 开启事务
     *
     * @return bool
     */
    public function startTrans()
    {
        if (! $this->linkId) {
            return false;
        }
        if (0 === $this->trans[$this->linkId]) {
            $this->links[$this->linkId]->beginTransaction();
        }
        $this->trans[$this->linkId] = 1;
        return true;
    }

    /**
     * 提交事务
     *
     * @return bool
     */
    public function commit()
    {
        if ($this->trans[$this->linkId] > 0) {
            try {
                $this->links[$this->linkId]->commit();
            } catch (\Exception $e) {
                error($e->getMessage(), $e->getTrace());
            }
            $this->trans[$this->linkId] = 0;
        }
        return true;
    }

    /**
     * 回滚事务
     *
     * @return bool
     */
    public function rollback()
    {
        if ($this->trans[$this->linkId] > 0) {
            try {
                $this->links[$this->linkId]->rollBack();
            } catch (\Exception $e) {
                error($e->getMessage(), $e->getTrace());
            }
            $this->trans[$this->linkId] = 0;
        }
        return true;
    }

    /**
     * 获取查询结果
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
        $this->links[$this->linkId] = null;
        $this->linkId = null;
    }

    /**
     * 绑定sql预处理变量
     *
     * @param string $key
     * @param mix    $val
     */
    public function bindParam($key, $val)
    {
        $this->bind[':' . $key] = $val;
    }

    /**
     * 添加数据
     *
     * @param array $data
     * @return bool|int|string
     */
    public function add($data = array())
    {
        $keys = '';
        $values = '';
        if (empty($data)) {
            if (empty($this->bind)) {
                return false;
            }
            $values = implode(',', array_keys($this->bind));
            $keys = preg_replace("/:/", '', $values);
        }
        foreach ($data as $name => $val) {
            $this->bindParam($name, $val);
            $keys .= '`' . $name . '`' . ',';
            $values .= ':' . $name . ',';
        }
        $sql = 'INSERT INTO ' . $this->table . '(' . rtrim($keys, ',') . ') VALUES (' . rtrim($values, ',') . ')';
        return $this->execute($sql);
    }

    /**
     * 更新数据
     *
     * @param array $data
     * @return bool|int|string
     */
    public function update($data = array())
    {
        $str = '';
        if (empty($data)) {
            if (empty($this->bind)) {
                return false;
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
     * 删除数据
     *
     * @param string $where
     * @return bool|int|string
     */
    public function delete($where = '')
    {
        if (! empty($where)) {
            $this->where($where);
        }
        $sql = 'DELETE FROM ' . $this->table
            . $this->opt['where']
            . $this->opt['limit'];
        return $this->execute($sql);
    }

    /**
     * 获取一行数据
     *
     * @param string $field
     * @param string $where
     * @return array|bool|mixed
     */
    public function getRow($field = '', $where = '')
    {
        if (! empty($field)) {
            $this->fields($field);
        }
        if (! empty($where)) {
            $this->where($where);
        }
        $this->opt['limit'] = ' LIMIT 1 ';
        $sql = 'SELECT ' . $this->opt['fields'] . ' FROM `' . $this->table . '`'
            . $this->opt['where']
            . $this->opt['limit'];

        $result = $this->query($sql);

        if (false !== $result) {
            if (empty($result)) {
                return array();
            } else {
                return $result[0];
            }
        }
        return false;
    }

    /**
     * 获取一个字段数据
     *
     * @param string $field
     * @param string $where
     * @return array|bool
     */
    public function getOne($field = '', $where = '')
    {
        if (! empty($field)) {
            $this->fields($field);
        }
        if (! empty($where)) {
            $this->where($where);
        }
        if (stripos($this->opt['fields'], ',')) {
            error('This method can only query a field, but a number of fields are presented.', []);
            return false;
        }
        $this->opt['limit'] = ' LIMIT 1 ';
        $sql = 'SELECT ' . $this->opt['fields'] . ' FROM ' . $this->table
            . $this->opt['where']
            . $this->opt['group']
            . $this->opt['order']
            . $this->opt['limit'];
        $result = $this->query($sql);
        if (false !== $result) {
            if (empty($result)) {
                return array();
            } else {
                return $result[0][$this->opt['fields']];
            }
        }
        return false;
    }

    /**
     * 获取所有满足条件的数据
     *
     * @param string $field
     * @param string $where
     * @param string $limit
     * @return array|bool
     */
    public function getAll($field = '', $where = '', $limit = '')
    {
        if (! empty($field)) {
            $this->fields($field);
        }
        if (! empty($where)) {
            $this->where($where);
        }
        if (! empty($limit)) {
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
     * 设置查询limit
     *
     * @param $limit
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
     * 设置查询order
     *
     * @param $order
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
     * 设置查询group
     *
     * @param $group
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
     * 设置查询where
     *
     * @param $where
     * @return $this
     */
    public function where($where)
    {
        $tmp = array();
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
     * 获取当前连接的PDO实例
     *
     * @return null|PDO
     */
    public function getDb()
    {
        if (! $this->linkId) {
            return $this->links[$this->linkId];
        }
        return null;
    }

    /**
     * 打印调试信息
     */
    public function debug()
    {
        echo '当前操作的表为：' . $this->table . '<br />';
        echo '执行语句为：' . $this->sql . '<br />';
    }

    /**
     * 析构函数
     */
    public function __destruct()
    {
        if (! empty($this->PDOStatement)) {
            $this->free();
        }
        $this->close();
    }
}