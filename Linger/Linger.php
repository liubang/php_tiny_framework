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
    private static $includes = array();

    public static function getApp($config)
    {
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

    public static function C($key = '', $val = '')
    {
        if (empty($value)) {
            return Config::getConfig($key);
        } else {
            return Config::setConfig($key, $val);
        }
    }
}