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

class Linger
{
    public static $config = array();

    public static $app = null;

    private static $includes = array();

    public static function getApp($config)
    {
        if (file_exists($config)) {
            static::$config = require $config;
        } else {
            die($config . '文件不存在');
        }

        self::init();

        if (self::$app === NULL) {
            self::$app = new App();
        }
        return self::$app;
    }

    private static function init()
    {
        spl_autoload_register(function($class) {
            if (false !== stripos($class, 'Controller') && false === stripos($class, 'Linger\\Core')) {
                $classPath = APP_ROOT . '/app/module/' . str_replace('\\', '/', $class) . '.class.php';
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

    public static function incFiles($filePath)
    {
        $file = md5($filePath);
        if (in_array($file, self::$includes) && self::$includes[$filePath] === 1) {
            return true;
        } else {
            require $filePath;
            self::$config[$file] = 1;
            return true;
        }
    }


    public static function C($name = '', $value = '')
    {
        if ('' === $name) {
            return static::$config;
        } else {
            if ('' === $value) {
                return static::$config[$name];
            } else {
                static::$config[$name] = $value;
                return true;
            }
        }
    }
}