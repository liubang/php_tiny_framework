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

namespace linger\driver\view;

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
         * 模板变量
         *
         * @var array
         */
        protected $vars = [];

        /**
         * LingerViewAbstract constructor.
         */
        public function __construct()
        {
                /**
                 * 由于视图不是单例，所以多次实例化视图类可能会导致多次声明LINGER，造成System Notice错误
                 * 所以此处应该先判断是否已经声明过该常量
                 */
                \defined('LINGER') || \define('LINGER', 'true');
                $this->tmplPath = APP_ROOT . '/' . APP_NAME . '/module/' . MODULE . '/view';
        }

        /**
         * 模板赋值
         *
         * @param $name
         * @param $value
         */
        public function assign($name, $value)
        {
                if (\is_array($name)) {
                        foreach ($name as $k => $v) {
                                $this->vars[$k] = $v;
                        }
                } else {
                        if (\is_string($name)) {
                                $this->vars[$name] = $value;
                        }
                }
        }

        /**
         * 输出模板
         *
         * @param $tplFile
         * @param $cacheTime
         * @param $cachePath
         * @param $contentType
         * @param $show
         *
         * @return mixed
         */
        abstract function display($tplFile, $cacheTime, $cachePath, $contentType, $show);

        /**
         * 渲染模板，返回渲染后的内容
         *
         * @param $tplFile
         * @param $cacheTime
         * @param $cachePath
         * @param $contentType
         *
         * @return mixed
         */
        abstract function render($tplFile, $cacheTime, $cachePath, $contentType);

        /**
         * 获取模板文件
         *
         * @return string
         */
        public function getTmpFile()
        {
                return $this->tmplFile;
        }

        /**
         * 获取编译后的文件
         *
         * @return string
         */
        public function getCompileFile()
        {
                return $this->compileFile;
        }

        /**
         * 获取模板路径
         *
         * @return string
         */
        public function getTmplPath()
        {
                return $this->tmplPath;
        }

        /**
         * 设置模板路径
         *
         * @param $tmplPath
         */
        public function setTmpPath($tmplPath)
        {
                $this->tmplPath = $tmplPath;
        }
}
