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
    /**
     * 注册模板变量
     *
     * @var array
     */
    private static $attr = array();

    /**
     * 模板文件的目录
     *
     * @var string
     */
    private $tmplPath = '';

    /**
     * View constructor.
     *
     * @param string $tmplPath
     */
    public function __construct($tmplPath = '')
    {
        if ('' !== $tmplPath) {
            $this->tmplPath = $tmplPath;
        }
    }

    /**
     * 设置视图模板文件目录
     *
     * @param $tmplPath
     */
    public function setTmpPath($tmplPath)
    {
        $this->tmplPath = $tmplPath;
    }

    /**
     * 获取视图文件目录
     *
     * @return string
     */
    public function getTmplPath()
    {
        return $this->tmplPath;
    }

    /**
     * 注册变量
     *
     * @param string $name 变量名
     * @param string $value 变量值
     */
    public function assign($name, $value)
    {
        self::$attr[$name] = $value;
    }

    /**
     * 解析并显示模板内容
     *
     * @param string $tmpl 模板文件
     */
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

    /**
     * 解析并返回模板内容
     *
     * @param string $tmpl 模板文件
     *
     * @return string 视图内容
     */
    public function render($tmpl)
    {
        ob_start();
        $this->display($tmpl);
        $html = ob_get_contents();
        ob_clean();
        return $html;
    }
}