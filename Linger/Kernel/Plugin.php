<?php
/*
 |------------------------------------------------------------------
 | 插件管理
 |------------------------------------------------------------------
 | @author    : liubang
 | @date      : 16/3/23 下午8:35
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace Linger\Kernel;

interface Plugin
{
        /**
         * @param Request  $request
         * @param Response $response
         *
         * @return mixed
         */
        public function routerStartup(Request $request, Response $response);

        /**
         * @param Request  $request
         * @param Response $response
         *
         * @return mixed
         */
        public function routerShutdown(Request $request, Response $response);

        /**
         * @param Request  $request
         * @param Response $response
         *
         * @return mixed
         */
        public function dispatchStartup(Request $request, Response $response);

        /**
         * @param Request  $request
         * @param Response $response
         *
         * @return mixed
         */
        public function dispatchShutdown(Request $request, Response $response);
}
