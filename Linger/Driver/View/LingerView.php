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

class LingerView extends LingerViewAbstract
{
    /**
     * LingerView constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param string $tplFile
     * @param int    $cacheTime
     * @param string $cachePath
     * @param string $contentType
     * @param bool   $show
     * @return null
     */
    public function display($tplFile, $cacheTime = -1, $cachePath = '', $contentType = 'text/html', $show = true)
    {
        if ($cacheTime > 0 && empty($cachePath)) {
            die('请配置缓存目录！');
        }
        $content = null;
        if (is_file($tplFile)) {
            $this->tmplPath = \dirname($tplFile);
            $this->tmplFile = $tplFile;
        } else {
            $this->tmplFile = $this->tmplPath . '/' . $tplFile;
        }
        if ($cacheTime > 0) {
            if (\is_file($cachePath)) {
                if (\time() - \filemtime($cachePath) <= $cacheTime) {
                    $content = \file_get_contents($cachePath);
                }
            }
        }
        if (! $content) {
            $this->compileFile = \C('TPL_COMP_PATH') . MODULE . '_' . CONTROLLER . '_' . ACTION . '_' . \substr(md5($this->tmplFile),
                    0, 8) . '.php';
            $this->compile();
            if (! empty($this->vars)) {
                extract($this->vars, EXTR_OVERWRITE);
            }
            ob_start();
            include $this->compileFile;
            $content = ob_get_clean();
            if ($cacheTime > 0) {
                \file_put_contents($cachePath, $content);
            }
        }
        if ($show) {
            $charset = C('TPL_CHARSET') ? C('TPL_CHARSET') : 'UTF-8';
            if (! \headers_sent()) {
                \header("Content-Type: {$contentType}; charset={$charset}");
            }
            echo $content;
        } else {
            return $content;
        }
    }

    /**
     * @param string $tplFile
     * @param int    $cacheTime
     * @param string $cachePath
     * @param string $contentType
     * @return null
     */
    public function render($tplFile = '', $cacheTime = -1, $cachePath = '', $contentType = 'text/html')
    {
        return $this->display($tplFile, $cacheTime, $cachePath, $contentType, false);
    }

    /**
     * @param $name
     * @param $value
     */
    public function assign($name, $value)
    {
        if (\is_array($name)) {
            foreach ($name as $key => $val) {
                $this->vars[$key] = $val;
            }
        } else {
            $this->vars[$name] = $value;
        }
    }

    /**
     * @return bool
     */
    private function compile()
    {
        if (! $this->compileInvallid()) {
            return false;
        }
        $compiler = new LingerCompiler();
        $compiler->run($this);
    }

    /**
     * @return bool
     */
    private function compileInvallid()
    {
        //TODO ...
        return true;
    }
}