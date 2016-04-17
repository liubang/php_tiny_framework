<?php
/*
 |------------------------------------------------------------------
 | 模板标签抽象类，所有扩展标签类都要继承该类
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 2016/3/29 12:11
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace Linger\Driver\View;

use Linger\Linger;

abstract class LingerTagAbstract
{
    /**
     * @var string
     */
    protected $tagLeft;

    /**
     * @var string
     */
    protected $tagRight;

    /**
     * @var array
     */
    protected $condition = [
        'eq'  => '==',
        'neq' => '<>',
        'gt'  => '>',
        'lt'  => '<',
        'egt' => '>=',
        'elt' => '<=',
    ];

    /**
     * @var array
     */
    protected $tag = [];

    /**
     * LingerTagAbstract constructor.
     */
    public function __construct()
    {
        $this->tagLeft = C('TPL_TAG_LEFT');
        $this->tagRight = C('TPL_TAG_RIGHT');
        if (method_exists($this, '__initalize')) {
            $this->__initalize();
        }
    }

    /**
     * 解析模板标签
     *
     * @param $tag
     * @param $viewContent
     * @return bool
     */
    public function parseTag($tag, &$viewContent)
    {
        if ($this->tag[$tag]['block']) {
            $preg = "#{$this->tagLeft}{$tag}\\s+(.*){$this->tagRight}(.*){$this->tagLeft}/{$tag}{$this->tagRight}#isU";
        } else {
            $preg = "#{$this->tagLeft}{$tag}\\s+(.*)/{$this->tagRight}#isU";
        }
        $status = preg_match_all($preg, $viewContent, $info, PREG_SET_ORDER);
        if ($status) {
            foreach ($info as $value) {
                if (empty($value[1])) {
                    $attr = [];
                } else {
                    $attr = $this->parseTagAttr($value[1]);
                }
                if (empty($value[2])) {
                    $value[2] = '';
                }
                $content = call_user_func_array(array($this, '_' . $tag), array($attr, $value[2], $value));
                $viewContent = str_replace($value[0], $content, $viewContent);
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * 解析模板标签属性
     *
     * @param $attrStr
     * @return array|bool
     */
    public function parseTagAttr($attrStr)
    {
        $preg = '/([a-zA-Z_]+)\s*=\s*(["\'])(.*)\2/iU';
        $status = preg_match_all($preg, $attrStr, $info, PREG_SET_ORDER);
        if ($status) {
            $attr = [];
            foreach ($info as $value) {
                $attr[$value[1]] = $this->parseAttrValue($value[3]);
            }
            return $attr;
        } else {
            return false;
        }
    }

    /**
     * 解析模板标签属性值
     *
     * @param $attrVal
     * @return mixed
     */
    public function parseAttrValue($attrVal)
    {
        foreach ($this->condition as $key => $val) {
            $attrVal = preg_replace("/\\s+$key\\s+/i", $val, $attrVal);
        }
        $const = get_defined_constants(true);
        foreach ($const['user'] as $name => $value) {
            if ('__' === substr($name, 0, 2)) {
                $attrVal = str_replace($name, $value, $attrVal);
            }
        }
        $preg = '/\$([a-zA-Z_\.]+)/i';
        $status = preg_match_all($preg, $attrVal, $info, PREG_SET_ORDER);
        if ($status) {
            foreach ($info as $key => $val) {
                $var = '';
                $data = explode('.', $val[1]);
                foreach ($data as $m => $n) {
                    if ($m == 0) {
                        $var .= $n;
                    } else {
                        $var .= '[\'' . $n . '\']';
                    }
                }
                $attrVal = str_replace($val[1], $var, $attrVal);
            }
        }
        return $attrVal;
    }

    /**
     * 获取自定义标签
     *
     * @return array
     */
    public function getTags()
    {
        return $this->tag;
    }
}