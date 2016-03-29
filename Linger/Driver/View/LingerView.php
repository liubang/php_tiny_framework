<?php
/*
 |------------------------------------------------------------------
 | linger.iliubang.cn
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 16/3/28 下午10:14
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace Linger\Driver\View;
use Linger\Linger;

class LingerView extends LingerViewAbstract
{
    public function __construct()
    {
        parent::__construct();
    }

    public function display($tplFile, $cacheTime = -1, $cachePath = '', $contentType = 'text/html', $show=true)
    {
        $cacheName = md5($_SERVER['REQUEST_URI']);
        $cacheTime = is_numeric($cacheTime) ? $cacheTime : intval(Linger::C('TPL_CACHE_TIME'));
        $cachePath = !empty($cachePath) ? $cachePath : Linger::C('TPL_CACHE_PATH');
        $content = null;
        if (is_file($tplFile)) {
            $this->tmplPath = dirname($tplFile);
            $this->tmplFile = $tplFile;
        } else {
            $this->tmplFile = $this->tmplPath . '/' . $tplFile;
        }
        if ($cacheTime > 0) {

        }
        if (!$content) {
            $this->compileFile = Linger::C('TPL_COMP_PATH') . MODULE . '_' . CONTROLLER . '_' . ACTION . '_' . substr(md5($this->tmplFile), 0, 8) . '.php';
            $this->compile();
            if (!empty($this->vars)) {
                extract($this->vars, EXTR_OVERWRITE);
            }
            ob_start();
            include $this->compileFile;
            $content = ob_get_clean();
            if ($cacheTime > 0) {

            }

            if ($show) {
                $charset = Linger::C('TPL_CHARSET') ? Linger::C('TPL_CHARSET') : 'UTF-8';
                if (!headers_sent()) {
                    header("Content-Type: {$contentType}; charset={$charset}");
                }
                echo $content;
            } else {
                return $content;
            }
        }
    }

    public function render($tplFile = '', $cacheTime = -1, $cachePath = '', $contentType = 'text/html')
    {
        return $this->display($tplFile, $cacheTime, $cachePath, $contentType, false);
    }

    public function assign($name, $value)
    {
        if (is_array($name)) {
            foreach($name as $key => $val) {
                $this->vars[$key] = $val;
            }
        } else {
            $this->vars[$name] = $value;
        }
    }

    private function compile()
    {
        if (!$this->compileInvallid()) {
            return false;
        }
        $compiler = new LingerCompiler();
        $compiler->run($this);
    }

    private function compileInvallid()
    {
        //TODO ...
        return true;
    }
}