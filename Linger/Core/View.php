<?php
/*
 |------------------------------------------------------------------
 | linger.iliubang.cn
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 16/3/24 上午12:04
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace Linger\Core;

class View
{
    private static $attr = array();

    private $tmplPath = '';

    public function __construct($tmplPath = '')
    {
        if ('' !== $tmplPath) {
            $this->tmplPath = $tmplPath;
        }
    }

    public function setTmpPath($tmplPath)
    {
        $this->tmplPath = $tmplPath;
    }

    public function getTmplPath()
    {
        return $this->tmplPath;
    }

    public function assign($name, $value)
    {
        self::$attr[$name] = $value;
    }

    public function display($tmpl)
    {
        extract(self::$attr, EXTR_OVERWRITE);
        $filePath = $this->tmplPath . '/' . $tmpl;
        if (file_exists($filePath)) {
            include $filePath;
        } else {
            die('模板文件' . $filePath . '不存在');
        }
    }

    public function render($tmpl)
    {
        ob_start();
        $this->display($tmpl);
        $html = ob_get_contents();
        ob_clean();
        return $html;
    }
}