<?php
/*
 |------------------------------------------------------------------
 | 注册表类
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
    /**
     * @var mixed[]
     */
    protected $registry = [];

    /**
     * @var null|self
     */
    private static $ins = null;

    /**
     * Registry constructor.
     */
    private function __construct()
    {

    }

    public static function getInstance()
    {
        if (null === self::$ins) {
            self::$ins = new self();
        }
        return self::$ins;
    }

    /**
     * 向注册表中注册一个变量
     *
     * @param string $key
     * @param mixed  $val
     */
    public function set($key, $val)
    {
        $this->registry[$key] = $val;
    }

    /**
     * 获取注册表
     *
     * @param null|string $key
     * @return mixed|\mixed[]|null
     */
    public function get($key = null)
    {
        if (null === $key) {
            return $this->registry;
        }
        if (isset($this->registry[$key])) {
            return $this->registry[$key];
        }
        return null;
    }

}