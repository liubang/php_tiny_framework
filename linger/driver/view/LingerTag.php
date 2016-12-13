<?php
/*
 |------------------------------------------------------------------
 | 扩展的模板标签类
 |------------------------------------------------------------------
 | @author    : liubang
 | @date      : 2016/3/29 12:09
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace linger\driver\view;

class LingerTag extends LingerTagAbstract
{
        protected $tag = [
                'foreach' => ['block' => 1, 'level' => 5],
                'for'     => ['block' => 1, 'level' => 3],
                'if'      => ['block' => 1, 'level' => 5],
                'elseif'  => ['block' => 0, 'level' => 0],
                'else'    => ['block' => 0, 'level' => 0],
                'import'  => ['block' => 0, 'level' => 0],
                'include' => ['block' => 0, 'level' => 0],
                'empty'   => ['block' => 1, 'level' => 5],
                'nempty'  => ['block' => 1, 'level' => 5],
        ];

        public function __initalize()
        {

        }

        public function _import($attr, $content)
        {
                if (!isset($attr['type']) || !isset($attr['file'])) {
                        \trigger_error('模板解析错误，import标签没有正确设置标签属性！', E_USER_ERROR);
                }
                if ($attr['type'] === 'js') {
                        return "<script type=\"text/javascript\" src=\"{$attr['file']}\"></script>";
                } else {
                        if ($attr['type'] === 'css') {
                                return "<link type=\"text/css\" rel=\"stylesheet\" href=\"{$attr['file']}\" />";
                        }
                }
        }

        public function _empty($attr, $content)
        {
                if (!isset($attr['condition'])) {
                        \trigger_error('模板解析错误，empty标签没有正确设置标签属性！', E_USER_ERROR);
                }
                return "<?php if (empty({$attr['condition']})) {?> {$content} <?php }?>";
        }

        public function _nempty($attr, $content)
        {
                if (!isset($attr['condition'])) {
                        \trigger_error('模板解析错误，nempty标签没有正确设置标签属性！', E_USER_ERROR);
                }
                return "<?php if (!empty({$attr['condition']})) {?> {$content} <?php }?>";
        }

        public function _for($attr, $content)
        {
                $step = isset($attr['step']) ? $attr['step'] : 1;
                $name = '$' . (isset($attr['name']) ? $attr['name'] : 'i');
                $comparison = isset($attr['comparison']) ? $attr['comparison'] : '<';
                if (isset($attr['start']) && isset($attr['end'])) {
                        return "for ({$name}={$attr['start']}; {$name} {$comparison} {$attr['end']}; $name+=$step ) { {$content} }";
                } else {
                        \trigger_error('模板解析错误，for标签没有正确设置标签属性！', E_USER_ERROR);
                }
        }

        public function _foreach($attr, $content)
        {
                if (!isset($attr['name']) || !isset($attr['item'])) {
                        \trigger_error('模板解析错误，foreach标签没有正确设置标签属性！', E_USER_ERROR);
                }
                if (isset($attr['key'])) {
                        $php = "<?php foreach ($" . $attr['name'] . " as $" . $attr['key'] . " => $" . $attr['item'] . ") { ?>";
                } else {
                        $php = '<?php foreach ($' . $attr['name'] . ' as $' . $attr['item'] . ') { ?>';
                }
                $php .= $content;
                $php .= '<?php } ?>';
                return $php;
        }

        public function _include($attr, $content)
        {
                if (isset($attr['file'])) {
                        $view = new LingerView();
                        return $view->render($attr['file']);
                } elseif (isset($attr['url'])) {
                        $url = $attr['url'];
                        if (!preg_match('/^(http|https)/', $attr['url'])) {
                                $url = (isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https' ?
                                                'https://' : 'http://')
                                        . $_SERVER['HTTP_HOST'] . '/'
                                        . preg_replace('/^(\/+)/', '', $url);
                        }
                        return file_get_contents($url);
                }
                return '';
        }

        public function _if($attr, $content)
        {
                if (!isset($attr['condition'])) {
                        \trigger_error('模板解析错误，if标签没有正确设置标签属性！', E_USER_ERROR);
                }
                return "<?php if ({$attr['condition']}) { ?> {$content} <?php }?>";
        }

        public function _elseif($attr, $content)
        {
                if (!isset($attr['condition'])) {
                        \trigger_error('模板解析错误，elseif标签没有正确设置标签属性！', E_USER_ERROR);
                }
                return "<?php } elseif ({$attr['condition']}) {?> ";
        }

        public function _else($attr, $content)
        {
                return "<?php } else  {?>";
        }
}
