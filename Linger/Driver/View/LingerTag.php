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

namespace Linger\Driver\View;

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
        'nempty'  => ['block' => 1, 'level' => 5]
    ];

    public function __initalize()
    {

    }

    public function _import($attr, $content)
    {
        if ($attr['type'] === 'js') {
            return "<script type=\"text/javascript\" src=\"{$attr['file']}\"></script>";
        }
        if ($attr['type'] === 'css') {
            return "<link type=\"text/css\" rel=\"stylesheet\" href=\"{$attr['file']}\" />";
        }
    }

    public function _empty($attr, $content)
    {
        return "<?php if (empty({$attr['condition']})) {?> {$content} <?php }?>";
    }

    public function _nempty($attr, $content)
    {
        return "<?php if (!empty({$attr['condition']})) {?> {$content} <?php }?>";
    }

    public function _for($attr, $content)
    {
        $step = isset($attr['step']) ? $attr['step'] : 1;
        $name = '$' . (isset($attr['name']) ? $attr['name'] : 'i');
        $comparison = isset($attr['comparison']) ? $attr['comparison'] : '<';
        if (isset($attr['start']) && isset($attr['end'])) {
            return "for ({$name}={$attr['start']}; {$name} {$comparison} {$attr['end']}; $name+=$step ) { {$content} }";
        }
    }

    public function _foreach($attr, $content)
    {
        if (isset($attr['key']) && isset($attr['item'])) {
            $php = "<?php foreach ($" . $attr['name'] . " as $" . $attr['key'] . " => $" . $attr['item'] . ") { ?>";
        } else {
            if (isset($attr['item'])) {
                $php = '<?php foreach ($' . $attr['name'] . ' as $' . $attr['item'] . ') { ?>';
            }
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
        }
        return '';
    }

    public function _if($attr, $content)
    {
        return "<?php if ({$attr['condition']}) { ?> {$content} <?php }?>";
    }

    public function _elseif($attr, $content)
    {
        return "<?php } elseif ({$attr['condition']}) {?> ";
    }

    public function _else($attr, $content)
    {
        return "<?php } else  {?>";
    }
}