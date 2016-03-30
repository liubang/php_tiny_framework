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
        //set_error_handler(array($this, 'appError'));
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
        //print_r($trace);die;
        $message = $e->getMessage();
        $this->response->error($message, $trace, 'Exception');
    }

    public function appError()
    {

    }
}