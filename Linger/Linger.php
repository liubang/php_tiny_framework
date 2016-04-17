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

define('LINGER_ROOT', realpath(dirname(__FILE__)));
require LINGER_ROOT . '/Common/helpers.php';

class Linger
{
    /**
     * initialize application
     */
    private static function init()
    {
        // regist the autoload function.
        spl_autoload_register(function ($class) {
            if (false === strpos($class, 'Linger\\Core')) {
                if (false !== stripos($class, 'Controller')) {
                    $classPath = APP_ROOT . '/' . APP_NAME . '/module/' . str_replace('\\', '/',
                            substr($class, 0, strlen($class) - 10)) . '.php';
                } elseif (false !== stripos($class, 'Model')) {
                    $classPath = APP_ROOT . '/' . APP_NAME . '/' . str_replace('\\', '/',
                            substr($class, 0, strlen($class) - 5)) . '.php';
                } elseif (false !== stripos($class, 'library')) {
                    $classPath = APP_ROOT . '/' . APP_NAME . '/' . str_replace('\\', '/', $class) . '.php';
                } elseif (false !== stripos($class, 'plugin')) {
                    $classPath = APP_ROOT . '/' . APP_NAME . '/' . str_replace('\\', '/',
                            substr($class, 0, strlen($class) - 6)) . '.php';
                } else {
                    $classPath = APP_ROOT . '/' . str_replace('\\', '/', $class) . '.php';
                }
            } else {
                $classPath = LINGER_ROOT . str_replace('Linger', '', str_replace('\\', '/', $class)) . '.php';
            }

            if (file_exists($classPath)) {
                _include($classPath);
            }
        });
    }

    /**
     * get the application instance
     *
     * @param array|string $config
     * @return App
     */
    public static function app($config)
    {
        self::init();
        return App::getInstance($config);
    }

}