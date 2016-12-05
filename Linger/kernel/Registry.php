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

namespace linger\kernel;

class Registry
{
        use traits\Singleton;

        const REGIST_ARR = TRUE;

        /**
         * @var mixed[]
         */
        protected $registry = [];

        /**
         * @return traits\Singleton|null|self
         */
        public static function singleton()
        {
                return self::getInstance();
        }

        /**
         * 向注册表中注册一个变量
         *
         * @param string $key
         * @param mixed  $val
         * @param bool   $isArr
         */
        public function set($key, $val, $isArr = FALSE)
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
         *
         * @return mixed|\mixed[]|null
         */
        public function get($key = NULL)
        {
                if (NULL === $key) {
                        return $this->registry;
                }
                if (isset($this->registry[$key])) {
                        return $this->registry[$key];
                }
                return NULL;
        }

}
