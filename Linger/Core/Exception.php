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
     * @var Response|null
     */
    private $response = null;

    /**
     * Exception constructor.
     */
    public function __construct()
    {
        // get instance
        $this->response = App::factory('Linger\\Core\\Response');

        // set custom exception handler
        set_exception_handler(array($this, 'appException'));

        // set custom error handler
        set_error_handler(array($this, 'appError'), E_ALL);
    }

    /**
     * @param \Exception $e
     */
    public function appException($e)
    {
        $trace = $e->getTrace();
        $message = $e->getMessage();
        foreach ($trace as $k => $v) {
            if (! isset($v['file'])) {
                $trace[$k]['file'] = '';
            }
            if (! isset($v['line'])) {
                $trace[$k]['line'] = 0;
            }
            if (! isset($v['class'])) {
                $trace[$k]['class'] = '';
            }
            if (! isset($v['function'])) {
                $trace[$k]['function'] = '';
            }
        }
        error($message, $trace, 'Exception');
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
                error($message, [], 'System Notice');
                break;
            case E_USER_NOTICE:
                error($message, [], 'Custom Notice');
                break;
            case E_WARNING:
            case E_COMPILE_WARNING:
            case E_CORE_WARNING:
                error($message, [], 'System Warning');
                break;
            case E_USER_WARNING:
                error($message, [], 'Custom Warning');
                break;
            case E_USER_ERROR:
                error($message, [], 'Custom Error');
                break;
            case E_ERROR:
            case E_COMPILE_ERROR:
            case E_CORE_ERROR:
            default :
                error($message, [], 'System Error');
                break;
        }
    }
}