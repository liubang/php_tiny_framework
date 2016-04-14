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
     * @param string $filePath
     * @return bool
     */
    function _include($filePath)
    {
        static $g_include = [];

        $file = md5($filePath);

        if (array_key_exists($file, $g_include) && 1 === $g_include[$file]) {
            return true;
        }

        require $filePath;
        $g_include[$file] = 1;
        return true;
    }
}

if (! function_exists('app')) {
    function app()
    {

    }
}

if (! function_exists('_404')) {
    function _404($code = false)
    {

    }
}
