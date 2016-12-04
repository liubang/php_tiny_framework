<?php
/*
 |------------------------------------------------------------------
 | linger.iliubang.cn
 |------------------------------------------------------------------
 | @author    : liubang
 | @date      : 2016/12/4 下午10:07
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace  linger\kernel\traits;


trait Singleton
{
        /**
         * @var null|\linger\kernel\*
         */
        private static $_instance = null;


        private function __construct()
        {

        }

        private static function getInstance()
        {
                if (!self::$_instance instanceof self) {
                        self::$_instance = new self();
                }

                return self::$_instance;
        }
}