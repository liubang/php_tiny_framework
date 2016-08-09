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
    const REGIST_ARR = true;

    /**
     * @var mixed[]
     */
    protected $registry = [];

    /**
     * Registry constructor.
     */
    public function __construct()
    {

    }

    /**
     * 向注册表中注册一个变量
     *
     * @param string $key
     * @param mixed  $val
     * @param bool   $isArr
     */
    public function set($key, $val, $isArr = false)
    {
        if ($isArr) {
            $this->registry[$key][] = $val;
        } else {
            $this->registry[$key] = $val;
        }
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