<?php
/*
 |------------------------------------------------------------------
 | linger.iliubang.cn
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 2016/3/29 14:01
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace Linger\Driver\View;

class LingerCompiler
{
    /**
     * @var LingerView
     */
    private $view = null;

    /**
     * @var string
     */
    private $content = '';

    /**
     * @var array
     */
    private $literal = [];

    public function run($view)
    {
        $this->view = $view;
        $this->content = file_get_contents($this->view->getTmpFile());
        $this->getNoParseContent();
        $this->parseTags();
        $this->parseVars();
        $this->parseFunc();
        $this->replaceNoParseContent();
        file_put_contents($this->view->getCompileFile(), $this->content);
    }

    private function getNoParseContent()
    {
        $preg = '#<literal>(.*?)</literal>#isU';
        $status = preg_match_all($preg, $this->content, $info, PREG_SET_ORDER);
        if ($status) {
            foreach($info as $key => $val) {
                if (!empty($val)) {
                    $this->literal[$key] = $val[1];
                    $this->content = str_replace($val[0], '###' . $key . '###', $this->content);
                }
            }
        }
    }

    private function replaceNoParseContent()
    {
        foreach ($this->literal as $k => $content) {
            $this->content = str_replace('###' . $k . '###', $content, $this->content);
        }
    }

    private function parseTags()
    {
        $tagObj = new LingerTag();
        $tags = $tagObj->getTags();
        foreach ($tags as $tag => $opt) {
            if (!isset($opt['block']) || !isset($opt['level'])) {
                continue;
            }
            for ($i = 0; $i < $opt['level']; $i++) {
                if (!$tagObj->parseTag($tag, $this->content)) {
                    break;
                }
            }
        }
    }

    /**
     * 解析变量 {$a}, {$a.b}, {$a|date=y-m-d,###}
     */
    private function parseVars()
    {
        $preg = '/\{\$([a-zA-Z\.\_\[\]\'\"]+)?(?:\|(.*))?\}/isU';
        //0. 全部 1. 变量名 2. 函数名=参数
        $status = preg_match_all($preg, $this->content, $info, PREG_SET_ORDER);
        if ($status) {
            foreach ($info as $value) {
                $var = '$';
                if (!empty($value[1])) {
                    $data = explode('.', $value[1]);
                    foreach ($data as $n => $m) {
                        if ($n == 0) {
                            $var .= $m;
                        } else {
                            $var .= '[\'' . $m . '\']';
                        }
                    }
                }
                if (!empty($value[2])) {
                    $funcs = explode('|', $value[2]);
                    foreach ($funcs as $func) {
                        $tmp = explode('=', $func);
                        $function = $tmp[0];
                        $args = isset($tmp[1]) ? $tmp[1] : '';
                        if (strstr($args, '###')) {
                            $args = str_replace('###', $var, $args);
                        } else {
                            $args = $var . ',' . $args;
                        }
                        $args = trim($args, ',');
                        $var = $function . '(' . $args . ')';
                    }
                }
                if (!empty($var)) {
                    $replace = "<?php echo $var;?>";
                    $this->content = str_replace($value[0], $replace, $this->content);
                }
            }
        }
    }

    private function parseFunc()
    {
        $preg = '/\{\:(.*)\}/isU';
        $status = preg_match_all($preg, $this->content, $info, PREG_SET_ORDER);
        if ($status) {
            foreach ($info as $v) {
                if (!empty($v[1])) {
                    $replace = "<?php echo {$v[1]}; ?>";
                    $this->content = str_replace($v[0], $replace, $this->content);
                }
            }
        }
    }
}