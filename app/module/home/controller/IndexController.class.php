<?php
/*
 |------------------------------------------------------------------
 | linger.iliubang.cn
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 16/3/23 下午9:02
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace home\controller;
use Linger\Core\Controller;
use model\UserModel;
use Linger\Linger;

class IndexController extends Controller
{
    /**
     * @var \model\UserModel
     */
    private $userModel = null;

    public function _init()
    {
        parent::_init();
        $this->userModel = new UserModel();
    }

    public function indexAction()
    {
        if (IS_GET) {
            print_r($this->get());
            //print_r($this->userModel->getUserInfoById(1034285));
        }
        //echo 'this is Home module index controller index action';
        //echo strtolower(preg_replace('/Controller/', '', trim(strrchr(__CLASS__, '\\'),  '\\')));
        $this->assign('aaa', 'hello world');
        $this->display();
    }


    public function listAction()
    {
        $this->assign('id', $_GET['id']);
        $this->display();
    }

    public function testAction()
    {
        $userModel = Linger::M('user');
//        $userModel->debug();
        $result = $userModel->fields(array('id', 'user_name', 'reg_date'))->where(array('id' => '2'))->getRow();
//        $userModel->debug();
        print_r($result);
    }
}