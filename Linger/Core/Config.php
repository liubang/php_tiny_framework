<?php
/*
 |------------------------------------------------------------------
 | 管理全局配置类
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 16/3/26 下午11:09
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace Linger\Core;

class Config
{
    /**
     * 全局配置项
     *
     * @var array
     */
    private static $g_config = array();

    /**
     * 初始化配置项
     *
     * @param string $conFile config文件的路径
     */
    public static function configInit($conFile)
    {
        if (file_exists($conFile)) {
            static::$g_config = array_merge(require LINGER_ROOT . '/Conf/config.php', require $conFile);
        } else {
            die($conFile . '文件不存在');
        }
    }

    /**
     * 获取配置项目
     *
     * @param string $key
     *
     * @return array
     */
    public static function getConfig($key = '')
    {
        if (empty($key)) {
            return static::$g_config;
        }
        return static::$g_config[$key];
    }

    /**
     * 设置配置项
     *
     * @param string $key
     * @param string $val
     */
    public static function setConfig($key, $val = '')
    {
        if (empty($val)) {
            static::$g_config = $key;
        } else {
            static::$g_config[$key] = $val;
        }
    }
}
