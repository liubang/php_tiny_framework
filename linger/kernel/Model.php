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

namespace linger\kernel;

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
         * @var \linger\driver\db\DbDriver|null
         */
        protected $db = NULL;

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
                } elseif (empty($this->table)) {
                        $this->table = \strtolower(str_replace('Model', '',
                                \ltrim(strrchr(get_called_class(), '\\'), '\\')));
                }

                if (!empty($database)) {
                        $this->database = $database;
                }

                $this->db = \D($this->database . '.' . $this->table);
        }

        /**
         * @param $table
         * @return $this
         */
        public function forTable($table)
        {
                $this->db->forTable($table);
                return $this;
        }

        /**
         * @param $sql
         * @return array|bool
         */
        public function query($sql)
        {
                return $this->db->query($sql);
        }

        /**
         * @param $sql
         * @return bool|int|string
         */
        public function execute($sql)
        {
                return $this->db->execute($sql);
        }

        /**
         * @return bool
         */
        public function startTrans()
        {
                return $this->db->startTrans();
        }

        /**
         * @return bool
         */
        public function commit()
        {
                return $this->db->commit();
        }

        /**
         * @return bool
         */
        public function rollback()
        {
                return $this->db->rollback();
        }

        /**
         * @param $key
         * @param $val
         * @return mixed
         */
        public function bindParam($key, $val)
        {
                return $this->bindParam($key, $val);
        }

        /**
         * @param array $data
         * @return bool|int|string
         */
        public function add($data = [])
        {
                return $this->db->add($data);
        }

        /**
         * @param array $data
         * @return bool|int|string
         */
        public function update($data = [])
        {
                return $this->db->update($data);
        }

        /**
         * @param string $where
         * @return bool|int|string
         */
        public function delete($where = '')
        {
                return $this->db->delete($where);
        }

        /**
         * @param string $field
         * @param string $where
         * @return array|bool|mixed
         */
        public function getRow($field = '', $where = '')
        {
                return $this->db->getRow($field, $where);
        }

        /**
         * @param string $field
         * @param string $where
         * @return array|bool
         */
        public function getOne($field = '', $where = '')
        {
                return $this->db->getOne($field, $where);
        }

        /**
         * @param string $field
         * @param string $where
         * @param string $limit
         * @return array|bool
         */
        public function getAll($field = '', $where = '', $limit = '')
        {
                return $this->db->getAll($field, $where, $limit);
        }

        /**
         * @param $arr
         * @return $this
         */
        public function fields($arr)
        {
                $this->db->fields($arr);
                return $this;
        }

        /**
         * @param $limit
         * @return $this
         */
        public function limit($limit)
        {
                $this->db->limit($limit);
                return $this;
        }

        /**
         * @param $order
         * @return $this
         */
        public function order($order)
        {
                $this->db->order($order);
                return $this;
        }

        /**
         * @param $group
         * @return $this
         */
        public function group($group)
        {
                $this->db->group($group);
                return $this;
        }

        /**
         * @param $where
         * @return $this
         */
        public function where($where)
        {
                $this->db->where($where);
                return $this;
        }

        /**
         * @return null|\PDO
         */
        public function getDb()
        {
                return $this->db->getDb();
        }

        public function debug()
        {
                $this->db->debug();
        }
}
