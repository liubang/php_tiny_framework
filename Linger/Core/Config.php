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
//
//    /**
//     * @var self|null
//     */
//    private static $ins = null;

    /**
     * Config constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param string $file
     * @param bool   $processSections
     * @param int    $scannerMode
     * @return array|mixed
     */
    private static function parseIniFile($file, $processSections = false, $scannerMode = INI_SCANNER_NORMAL)
    {
        $explodeStr = '.';
        $escapeChar = "'";
        $data = parse_ini_file($file, $processSections, $scannerMode);
        if (! $processSections) {
            $data = array($data);
        }
        foreach ($data as $sectionKey => $section) {
            foreach ($section as $key => $value) {
                if (strpos($key, $explodeStr)) {
                    if (substr($key, 0, 1) !== $escapeChar) {
                        $subKeys = explode($explodeStr, $key);
                        $subs = &$data[$sectionKey];
                        foreach ($subKeys as $subKey) {
                            if (! isset($subs[$subKey])) {
                                $subs[$subKey] = '';
                            }
                            $subs = &$subs[$subKey];
                        }
                        $subs = $value;
                        unset($data[$sectionKey][$key]);
                    } else {
                        $newKey = trim($key, $escapeChar);
                        $data[$sectionKey][$newKey] = $value;
                        unset($data[$sectionKey][$key]);
                    }
                }
            }
        }
        if (! $processSections) {
            $data = $data[0];
        }
        return $data;
    }

    /**
     * @param $config
     * @return array
     */
    private static function changeArrayKeyCase($config)
    {
        $arr = [];
        foreach ($config as $key => $val) {
            $key = strtolower($key);
            $arr[$key] = is_array($val) ? self::changeArrayKeyCase($val) : $val;
        }
        return $arr;
    }

    /**
     * @param $config
     * @return $this
     */
    public function loadConfig($config)
    {
        if (is_string($config)) {
            if (is_file($config)) {
                $ext = substr($config, strlen($config) - 4);
                if ($ext === '.ini') {
                    $config = self::parseIniFile($config);
                }
                if ($ext === '.php') {
                    $config = require $config;
                }
            } else {
                exit($config . '文件不存在');
            }
        }
        if (is_array($config)) {
            $this->g_config = array_merge(require LINGER_ROOT . '/Conf/config.php', $config);
        } else {
            exit('请传入正确的配置文件或配置数组');
        }

        $this->g_config = self::changeArrayKeyCase($this->g_config);

        return $this;
    }
//
//    /**
//     * @return Config|null
//     */
//    public static function getInstance()
//    {
//        if (null === self::$ins) {
//            self::$ins = new self();
//        }
//        return self::$ins;
//    }

    /**
     * 获取配置项目
     *
     * @param string $key
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
            $this->g_config = self::changeArrayKeyCase($key);
        } else {
            $key = strtolower($key);
            $this->g_config[$key] = $val;
        }
    }
}
