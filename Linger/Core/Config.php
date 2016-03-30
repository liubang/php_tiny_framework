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
    private $g_config = array();

    /**
     * @var self|null
     */
    private static $ins = null;

    private function __construct($conFile)
    {
        if (file_exists($conFile)) {
            $this->g_config = array_merge(require LINGER_ROOT . '/Conf/config.php', require $conFile);
            $fun = function($arr) use (&$fun){
                $rs = [];
                foreach ($arr as $k => $v) {
                    $rs[strtolower($k)] = is_array($v) ? $fun($v) : $v;
                }
                return $rs;
            };
            $this->g_config = $fun($this->g_config);
        } else {
            die($conFile . '文件不存在');
        }
    }

    /**
     * @param $conFile
     *
     * @return self
     */
    public static function getInstance($conFile = '')
    {
        if (null === self::$ins) {
            self::$ins = new self($conFile);
        }
        return self::$ins;
    }

    /**
     * 获取配置项目
     *
     * @param string $key
     *
     * @return array
     */
    public function getConfig($key = '')
    {
        if (empty($key)) {
            return $this->g_config;
        }
        $key = strtolower($key);
        if (strpos($key, '.')) {
            $val = $this->g_config;
            $keys = explode('.', $key);
            foreach ($keys as $key) {
                if (isset($val[$key])) {
                    $val = $val[$key];
                } else {
                    return false;
                }
            }
            return $val;
        }
        return $this->g_config[$key];
    }

    /**
     * 设置配置项
     *
     * @param string $key
     * @param string $val
     */
    public function setConfig($key, $val = '')
    {
        if (empty($val)) {
            $this->g_config = $key;
        } else {
            $this->g_config[$key] = $val;
        }
    }
}
