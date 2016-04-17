<?php
/*
 |------------------------------------------------------------------
 | linger.iliubang.cn
 |------------------------------------------------------------------
 | @author    : liubang
 | @date      : 16/3/23 下午8:28
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

if (! function_exists('_include')) {
    /**
     * fast include a file, that would include the same file once time.
     *
     * @param string $filePath
     * @return bool
     */
    function _include($filePath)
    {
        static $g_include = [];

        if (in_array($filePath, $g_include)) {
            return true;
        }

        require $filePath;
        array_push($g_include, $filePath);
        return true;
    }
}

if (! function_exists('_default')) {
    /**
     * if the variable of name is not empty, it will return the value of name, but return var.
     *
     * @param string $name
     * @param string $var
     * @return string
     */
    function _default($name, $var = '')
    {
        if (empty($name) || ! isset($name)) {
            return $var;
        }
        return $name;
    }
}

if (! function_exists('p')) {
    /**
     * print formated array.
     *
     * @param array $arr the array you want to print.
     */
    function p(array $arr)
    {
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    }
}

if (! function_exists('C')) {
    /**
     * get or set config
     *
     * @param null|string       $key the config key you want to set or get, if is null, will return you all configs
     * @param null|string|array $val the config key value you want to set, if is null, will return you the config
     * @return array|void
     */
    function C($key = null, $val = null)
    {
        $config = \Linger\Core\Config::getInstance();
        if (null === $key) {
            return $config->getConfig();
        } elseif (null === $val) {
            return $config->getConfig($key);
        } else {
            $config->setConfig($key, $val);
        }
    }
}

if (! function_exists('M')) {
    /**
     * fast instantiates a Model object.
     *
     * @param $table
     * @return \Linger\Driver\Db\DbDriver
     */
    function M($table)
    {
        /**
         * var \Linger\Driver\Db\DbDriver[]
         */
        static $g_model = [];
        // if the model of the table was not instantiated
        if (! isset($g_model[$table]) || empty($g_model[$table])) {
            // get the config of the db
            $config['db_host'] = C('DB_HOST');
            $config['db_user'] = C('DB_USER');
            $config['db_pwd'] = C('DB_PWD');
            $config['db_name'] = C('DB_NAME');
            $config['db_port'] = C('DB_PORT');
            $config['db_char'] = C('DB_CHAR');
            $config['db_prefix'] = C('DB_PREFIX');
            $config['db_socket'] = C('DB_SOCKET');
            $config['db_params'] = C('DB_PARAMS');
            $config['db_dsn'] = C('DB_DSN');
            $db = new \Linger\Driver\Db\MySql($config);
            $g_model[$table] = $db->connect()->forTable($table);
        }
        return $g_model[$table];
    }
}