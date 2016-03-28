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
    private static $includes = array();

    /**
     * 框架主文件初始化
     */
    private static function init()
    {
        spl_autoload_register(function($class) {
            if (false !== stripos($class, 'Controller') && false === stripos($class, 'Linger\\Core')) {
                $classPath = APP_ROOT . '/app/module/' . str_replace('\\', '/', $class) . '.class.php';
            } else if (false !== stripos($class, 'Model') && false === stripos($class, 'Linger\\Core')) {
                $classPath = APP_ROOT . '/app/' . str_replace('\\', '/', $class) . '.class.php';
            } else {
                $classPath = APP_ROOT . '/' . str_replace('\\', '/', $class) . '.php';
            }
            if (file_exists($classPath)) {
                self::incFiles($classPath);
            } else {
                die($classPath . '文件不存在');
            }
        });
    }

    /**
     * 获取app
     *
     * @param string $config
     *
     * @return \Linger\Core\App
     */
    public static function getApp($config)
    {
        self::init();
        return App::getInstance($config);
    }

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
     * @return array
     */
    public static function C($key = '', $val = '')
    {
        if (empty($val)) {
            return Config::getConfig($key);
        } else {
            Config::setConfig($key, $val);
        }
    }
}