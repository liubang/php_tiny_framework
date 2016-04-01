<?php
/*
 |------------------------------------------------------------------
 | linger.iliubang.cn
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 2016/3/31 14:59
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace Linger\Core;

class Registry
{
    protected static $g_registry = [];

    public static function set($key, $val)
    {
        self::$g_registry[$key] = $val;
    }

    public function get($key = null)
    {
        if (empty($key)) {
            return self::$g_registry;
        }
        return self::$g_registry[$key];
    }
}