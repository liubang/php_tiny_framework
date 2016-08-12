<?php
/*
 |------------------------------------------------------------------
 | 简单的模板引擎
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 2016/3/29 15:48
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */
namespace Linger\Driver\View;

class LingerViewSimple extends LingerViewAbstract
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param string $tplFile
     * @param int    $cacheTime
     * @param null   $cachePath
     * @param string $contentType
     * @param bool   $show
     * @return void
     */
    public function display($tplFile, $cacheTime = -1, $cachePath = null, $contentType = 'text/html', $show = true)
    {
        \extract($this->vars, EXTR_OVERWRITE);
        $filePath = $this->tmplPath . '/' . $tplFile;
        if (\file_exists($filePath)) {
            include $filePath;
        } else {
            die('模板文件' . $filePath . '不存在');
        }
    }

    /**
     * @param        $tplFile
     * @param int    $cacheTime
     * @param null   $cachePath
     * @param string $contentType
     * @return mixed
     */
    public function render($tplFile, $cacheTime = -1, $cachePath = null, $contentType = 'text/html')
    {
        \ob_start();
        $this->display($tplFile, $cacheTime, $cachePath, $contentType, false);
        $html = \ob_get_contents();
        \ob_clean();
        return $html;
    }
}