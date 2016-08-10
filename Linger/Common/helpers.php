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

use Linger\Driver\Db\MySql;
use Linger\Core\App;


defined('SHOW_404_PAGE') || define('SHOW_404_PAGE', 1);
defined('SHOW_403_PAGE') || define('SHOW_403_PAGE', 1);

if (!function_exists('app')) {

    /**
     * @param null $config
     *
     * @return \Linger\Core\App|null
     */
    function app($config = NULL)
    {
        return App::factory('Linger\\Core\\App', $config);
    }
}

if (!function_exists('_include')) {

    /**
     * fast include a file, that would include the same file once time.
     *
     * @param string $filePath
     *
     * @return bool
     */
    function _include($filePath)
    {
        static $g_include = [];

        if (in_array($filePath, $g_include)) {
            return TRUE;
        }

        require $filePath;
        array_push($g_include, $filePath);
        return TRUE;
    }
}

if (!function_exists('_default')) {

    /**
     * if the variable of name is not empty, it will return the value of name, but return var.
     *
     * @param string $name
     * @param string $var
     *
     * @return string
     */
    function _default($name, $var = '')
    {
        if (empty($name) || !isset($name)) {
            return $var;
        }
        return $name;
    }
}

if (!function_exists('p')) {

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

if (!function_exists('C')) {

    /**
     * get or set config
     *
     * @param null|string       $key the config key you want to set or get, if is null, will return you all configs
     * @param null|string|array $val the config key value you want to set, if is null, will return you the config
     *
     * @return array|void
     */
    function C($key = NULL, $val = NULL)
    {
        /**
         * @var \Linger\Core\Router
         */
        $config = app()->getConfig();

        if (NULL === $key) {
            return $config->get();
        } elseif (NULL === $val) {
            return $config->get($key);
        } else {
            $config->set($key, $val);
        }
    }
}

if (!function_exists('M')) {

    /**
     * fast instantiates a Model object.
     *
     * @param string $table
     *
     * @return \Linger\Driver\Db\DbDriver
     */
    function M($table)
    {
        /**
         * var \Linger\Driver\Db\DbDriver[]
         */
        static $g_model = [];
        // if the model of the table was not instantiated
        if (!isset($g_model[$table]) || empty($g_model[$table])) {
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
            $db = new MySql($config);
            $g_model[$table] = $db->connect()->forTable($table);
        }
        return $g_model[$table];
    }
}

if (!function_exists('_404')) {

    /**
     * fast response 404 status or custom 404 page.
     *
     * @param bool|null SHOW_404_PAGE
     *
     * @throws \HttpResponseException
     */
    function _404($showPage = FALSE)
    {
        if (!$showPage) {
            $response = app()->getResponse();
            $response->code(404);
            $response->send();
        } else {
            include C('TMPL_ACTION_404');
            exit;
        }
    }
}


if (!function_exists('_403')) {

    /**
     * fast response 403 status or custom 403 page.
     *
     * @param bool|null SHOW_403_PAGE
     *
     * @throws \HttpResponseException
     */
    function _403($showPage = FALSE)
    {
        if (!$showPage) {
            $response = app()->getResponse();
            $response->code(403);
            $response->send();
        } else {
            include C('TMPL_ACTION_403');
            exit;
        }
    }
}