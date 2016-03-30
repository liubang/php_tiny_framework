<?php
/*
 |------------------------------------------------------------------
 | 处理路由
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 16/3/23 下午8:34
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace Linger\Core;

use Linger\Linger;

class Router
{
    /**
     * 自定义路由规则
     *
     * @var array
     */
    private $roules = array();

    /**
     * @var string
     */
    private $uri = '';

    /**
     * @var self
     */
    private static $ins = null;

    /**
     * @var \Linger\Core\Dispatcher
     */
    private $dispatcher = null;

    /**
     * Router constructor.
     */
    private function __construct()
    {
        $this->roules = Linger::C('ROUTE');
        $this->dispatcher = Dispatcher::getInstance();
    }

    /**
     * @return Router
     */
    public static function getInstance()
    {
        if (null === self::$ins) {
            self::$ins = new self();
        }
        return self::$ins;
    }

    /**
     * 解析路由
     * 会优先解析在config文件中自定义的路由
     *
     * @return $this
     */
    public function parseUri()
    {
        $this->uri = trim(preg_replace('/^(?:index\.php|\/index\.php)?(.*?)/i', '\1', $_SERVER['REQUEST_URI']), '/');
        foreach ($this->roules as $key => $value) {
            if (preg_match('#' . $key . '#', $this->uri)) {
                $this->uri = preg_replace('#' . $key . '#', $value, $this->uri);
            }
        }
        $this->uri = preg_replace('/^(.*?)(?:\.html)/i', '\1', trim($this->uri, '/'));
        return $this;
    }

    /**
     * 添加路由规则
     *
     * @param array|string $rouls
     * @param string|null  $ref
     */
    public function addRout($rouls, $ref = null)
    {
        if (null === $ref) {
            if (is_array($rouls)) {
                $this->roules = array_merge($this->roules, $rouls);
            }
        } else {
            if (is_string($rouls) && is_string($ref)) {
                $this->roules[$rouls] = $ref;
            }
        }
    }

    /**
     * 分发路由
     */
    public function dispatch()
    {
        Request::getInstance();
        $this->dispatcher->dispatch($this->uri);
    }
}