<?php
/*
 |------------------------------------------------------------------
 | linger.iliubang.cn
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 16/3/23 下午8:34
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace Linger\Core;

class App
{
    private $router = null;

    public function __construct()
    {
        $this->router = new Router();
    }

    public function bootstrap()
    {
        if (file_exists(APP_ROOT . '/' . APP_NAME . '/Bootstrap.php')) {
            if (class_exists('\\'.APP_NAME.'\\Bootstrap')) {
                $initFuncs = get_class_methods('\\'.APP_NAME.'\\Bootstrap');
                foreach ($initFuncs as $func) {
                    if (substr($func, 0, 5) === '_init') {
                        call_user_func_array(array('\\'.APP_NAME.'\\Bootstrap', $func), array('app'=>$this, 'router' => $this->router));
                    }
                }
            }
        }
        return $this;
    }

    public function run()
    {
        $this->router->parseUri()->dispatch();
    }


}