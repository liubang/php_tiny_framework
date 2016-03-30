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
namespace Linger;

use Linger\Core\App;
use Linger\Core\Config;

class Linger
{
    /**
     * @var array
     */
    private static $includes = [];

    /**
     * @var \Linger\Driver\Db\DbDriver[]
     */
    private static $g_dao = [];

    /**
     * 框架主文件初始化
     */
    private static function init()
    {
        define('LINGER_ROOT', realpath(dirname(__FILE__)));

        spl_autoload_register(function($class) {
            if (false !== stripos($class, 'Controller') && false === strpos($class, 'Linger\\Core')) {
                $classPath = APP_ROOT . '/app/module/' . str_replace('\\', '/', substr($class, 0, strlen($class) - 10)) . '.php';
            } else if (false !== stripos($class, 'Model') && false === strpos($class, 'Linger\\Core')) {
                $classPath = APP_ROOT . '/app/' . str_replace('\\', '/', substr($class, 0, strlen($class) - 5)) . '.php';
            } else if (false !== strpos($class, 'Linger\\Core')) {
                $classPath = LINGER_ROOT .  str_replace('Linger', '', str_replace('\\', '/', $class)) . '.php';
            } else {
                $classPath = APP_ROOT . '/' . str_replace('\\', '/', $class) . '.php';
            }
            if (file_exists($classPath)) {
                self::incFiles($classPath);
            } 
        });
    }

    /**
     * @param array $config
     *
     * @return App
     */
    public static function getApp($config = [])
    {
        self::init();
        return App::getInstance($config);
    }

    /**
     * @param $filePath
     *
     * @return bool
     */
    public static function incFiles($filePath)
    {
        $file = md5($filePath);
        if (in_array($file, self::$includes) && 1 === self::$includes[$filePath]) {
            return true;
        } else {
            require $filePath;
            self::$includes[$file] = 1;
            return true;
        }
    }

    /**
     * 读取/设置配置项
     *
     * @param string $key
     * @param string $val
     *
     * @return array|string
     */
    public static function C($key = '', $val = '')
    {
        $config = Config::getInstance();
        if (empty($key)) {
            return $config->getConfig();
        } else if (empty($val)) {
            return $config->getConfig($key);
        } else {
            $config->setConfig($key, $val);
        }

    }

    /**
     * @param $table
     * @return Driver\Db\DbDriver
     */
    public static function M($table)
    {
        $config['db_host'] = self::C('DB_HOST');
        $config['db_user'] = self::C('DB_USER');
        $config['db_pwd'] = self::C('DB_PWD');
        $config['db_name'] = self::C('DB_NAME');
        $config['db_port'] = self::C('DB_PORT');
        $config['db_char'] = self::C('DB_CHAR');
        $config['db_prefix'] = self::C('DB_PREFIX');
        $config['db_socket'] = self::C('DB_SOCKET');
        $config['db_params'] = self::C('DB_PARAMS');
        $config['db_dsn'] = self::C('DB_DSN');
        if (!isset(self::$g_dao[$table]) || empty(self::$g_dao[$table])) {
            $db = new Driver\Db\MySql($config);
            self::$g_dao[$table] = $db->connect()->forTable($table);
        }
        return self::$g_dao[$table];
    }

    public static function _default($name, $var = '')
    {
        if (empty($name) || !isset($name)) {
            return $var;
        }
        return $name;
    }

}