<?php
/*
 |------------------------------------------------------------------
 | linger.iliubang.cn
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 2016/3/30 15:15
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace Linger\Core;

class Exception
{
    /**
     * @var null|self
     */
    private static $ins = null;

    /**
     * @var Response|null
     */
    private $response = null;

    private function __construct()
    {
        $this->response = Response::getInstance();
        set_exception_handler(array($this, 'appException'));
        set_error_handler(array($this, 'appError'), E_ALL);
    }

    public static function getInstance()
    {
        if (null === self::$ins) {
            self::$ins = new self();
        }
        return self::$ins;
    }

    /**
     * @param \Exception $e
     */
    public function appException($e)
    {
        $trace = $e->getTrace();
        $message = $e->getMessage();
        $this->response->error($message, $trace, 'Exception');
    }

    /**
     * 错误处理
     *
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     */
    public function appError($errno, $errstr, $errfile, $errline)
    {
        $message = "Custom Error: [{$errno}] {$errstr} on {$errfile} [{$errline}]";
        switch ($errno) {
            case E_NOTICE:
                $this->response->error($message, [], 'System Notice');
                break;
            case E_USER_NOTICE:
                $this->response->error($message, [], 'Custom Notice');
                break;
            case E_WARNING:
            case E_COMPILE_WARNING:
            case E_CORE_WARNING:
                $this->response->error($message, [], 'System Warning');
                break;
            case E_USER_WARNING:
                $this->response->error($message, [], 'Custom Warning');
                break;
            case E_USER_ERROR:
                $this->response->error($message, [], 'Custom Error');
                break;
            case E_ERROR:
            case E_COMPILE_ERROR:
            case E_CORE_ERROR:
            default :
                $this->response->error($message, [], 'System Error');
                break;
        }
    }
}