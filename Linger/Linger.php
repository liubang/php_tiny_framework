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

defined('LINGER_ROOT') || define('LINGER_ROOT', realpath(dirname(__FILE__)));
require LINGER_ROOT . '/Common/helpers.php';

spl_autoload_register(function ($class) {
        if (FALSE === strpos($class, 'Linger\\Kernel')) {
                if (FALSE !== strpos($class, 'Controller')) {
                        $classPath = APP_ROOT . '/' . APP_NAME . '/module/' . str_replace('\\', '/', $class) . '.php';
                } elseif (FALSE !== strpos($class, 'Model') || FALSE !== strpos($class,
                                'library') || FALSE !== strpos($class, 'plugin')
                ) {
                        $classPath = APP_ROOT . '/' . APP_NAME . '/' . str_replace('\\', '/', $class) . '.php';
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
