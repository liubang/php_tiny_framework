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

use Linger\Linger;
use Linger\Driver\View\LingerView;
use Linger\Driver\View\LingerViewSimple;

class View
{
    /**
     * @var \Linger\Driver\View\LingerViewAbstract
     */
    protected $viewObj = null;

    /**
     * View constructor.
     *
     * @param null   $viewClass
     * @param string $tmplPath
     */
    public function __construct($tmplPath = '', $viewClass = null)
    {
        if (is_null($viewClass)) {
            $viewDriver = Linger::C('VIEW_DRIVER');
            if ('simple' === $viewDriver) {
                $this->viewObj = new LingerViewSimple();
            }
            if ('linger' === $viewDriver) {
                $this->viewObj = new LingerView();
            }
        } else {
            if (is_subclass_of($viewClass, '\\Linger\\Driver\\View\\LingerViewAbstract')) {
                $this->viewObj = new $viewClass;
            } else {
                return false;
            }
        }
        if (empty($tmplPath)) {
            $tmplPath = APP_ROOT . '/' . APP_NAME . '/module/' . MODULE . '/view';
        }

        $this->viewObj->setTmpPath($tmplPath);
    }

    /**
     * @param $name
     * @param $value
     */
    public function assign($name, $value)
    {
        $this->viewObj->assign($name, $value);
    }

    /**
     * @param        $tplFile
     * @param int    $cacheTime
     * @param null   $cachePath
     * @param string $contentType
     * @param bool   $show
     * @return mixed
     */
    public function display($tplFile, $cacheTime = -1, $cachePath = null, $contentType = 'text/html', $show = true)
    {
        return $this->viewObj->display($tplFile, $cacheTime, $cachePath, $contentType, $show);
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
        return $this->viewObj->render($tplFile, $cacheTime, $cachePath, $contentType);
    }
}