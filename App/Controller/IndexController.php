<?php
/**
 * Created by PhpStorm.
 * User: shellvon
 * Date: 16/4/14
 * Time: 下午5:13.
 */

namespace Controller;

use MicroMan\MicroController;
use Microman\MicroUtility;
use Model;
use Utility\ValidateHelper;

class IndexController extends MicroController
{
    public function index()
    {
        $this->displayJson(array(
            'hello' => $_SESSION['username'],
        ));
    }

    public function before()
    {
        parent::before(); // TODO: Change the autogenerated stub

        if (!isset($_SESSION['uid']) && strcasecmp($this->action_name, 'login') !== 0) {
            $this->redirectExit('/index/login');
        }
    }

    /**
     * 登录页面.
     */
    public function login()
    {
        $data = array(
            'title' => '钱呢去哪儿呢 | Login',
        );
        if ($this->isPost()) {
            $result = $this->doLogin();
            if ($result === true) {
                $this->redirectExit('/index/index');
            }
            $data['error_msg'] = $result;
        }
        $this->displayTpl($data);
    }

    /**
     * 登录操作.
     *
     * @return bool|string
     */
    private function doLogin()
    {
        $payloads = MicroUtility::getMultiPost(array('username', 'password'));
        $validator = new ValidateHelper();
        $rules = array(
            array('username,password', 'required'), // 必须且非空
            array('username,password', 'useRegex', 'reg' => '/[a-zA-Z0-9]{6,10}/'),
        );
        $result = $validator->addValidator(array())->isValid($payloads, $rules);
        if ($result !== true) {
            return $result[0];
        }

        $username = $payloads['username'];
        $password = $payloads['password'];

        $user_info = Model\UserModel::getInstance()->find($username, $password);
        if (empty($user_info)) {
            return '用户名或密码错误';
        }
        $_SESSION['uid'] = $user_info->id;
        $_SESSION['username'] = $user_info->username;
        if (MicroUtility::getPost('remember') == 1) {
            // write cookie to auto login.
        }

        return true;
    }

    /**
     * 退出登录.仅POST才会退出.
     */
    public function logout()
    {
        if (!$this->isPost()) {
            $this->page404();
        } else {
            session_destroy();
            $_SESSION = array();
            $this->redirectExit('/index/login');
        }
    }
}
