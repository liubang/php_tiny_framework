<?php
/*
 |------------------------------------------------------------------
 | 模板引擎抽象类，扩展的所有视图类都要继承该类
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 2016/3/29 15:48
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace Linger\Driver\View;

abstract class LingerViewAbstract
{
    /**
     * 模板文件
     *
     * @var string
     */
    protected $tmplFile = '';

    /**
     * @var string
     */
    protected $tmplPath = '';

    /**
     * 编译文件
     *
     * @var string
     */
    protected $compileFile = '';

    /**
     * @var array
     */
    protected $vars = [];
    
    public function __construct()
    {
        define('LINGER', 'true');
        $this->tmplPath = APP_ROOT . '/' . APP_NAME . '/module/' . MODULE . '/view';
    }

    public function assign($name, $value)
    {
        if (is_array($name)) {
            foreach ($name as $k => $v) {
                $this->vars[$k] = $v;
            }
        } else if (is_string($name)) {
            $this->vars[$name] = $value;
        }
    }

    abstract function display($tplFile, $cacheTime, $cachePath, $contentType, $show);

    abstract function render($tplFile, $cacheTime, $cachePath, $contentType);

    public function getTmpFile()
    {
        return $this->tmplFile;
    }

    public function getCompileFile()
    {
        return $this->compileFile;
    }

    public function getTemplateFile()
    {
        return $this->tmplPath . '/' . md5(MODULE . CONTROLLER . ACTION);
    }

    public function getTmplPath()
    {
        return $this->tmplPath;
    }

    public function setTmpPath($tmplPath)
    {
        $this->tmplPath = $tmplPath;
    }
}