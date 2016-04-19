<?php
/**
 * Created by PhpStorm.
 * User: shellvon
 * Date: 16/4/19
 * Time: 下午3:24.
 */

namespace Controller;

use Model\CostTypeModel;
use Model\UserModel;
use MicroMan\MicroUtility;
use Utility\Utility;
use Utility\ValidateHelper;

class UserController extends BaseController
{
    /**
     * 登录页面.
     */
    public function login()
    {
        $data = array(
            'title' => '钱呢去哪儿呢 | Login',
            'error_msg' => isset($_SESSION['error_msg']) ? $_SESSION['error_msg'] : '',
        );
        if ($this->isPost()) {
            $result = $this->doLogin();
            if ($result === true) {
                $this->redirectExit('/');
            }
            $data['error_msg'] = $result;
        }
        if (isset($_SESSION['uid'])) {
            $this->redirectExit('/');
        }
        $this->displayTpl($data, null, false);
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
            array('username,password', 'useRegex', 'reg' => '/^[a-zA-Z0-9]{5,10}$/'),
        );
        $result = $validator->isValid($payloads, $rules);
        if ($result !== true) {
            return $result[0];
        }
        $salt = 'MicroManWebApp';
        //TODO: 加密用户密码.
        #$payloads['password'] = md5($payloads['password'].$salt);
        $user_info = UserModel::getInstance()->getOne($payloads);
        if (empty($user_info)) {
            return '用户名或密码错误';
        }
        $_SESSION['uid'] = $user_info['id'];
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
            $this->redirectExit('/user/login');
        }
    }

    /**
     * 注册接口.
     */
    public function register()
    {
        $data = array(
            'title' => '钱呢去哪儿呢 | Register',
            'error_msg' => '注册一个么',
        );
        if ($this->isPost()) {
            $result = $this->doRegister();
            if ($result === true) {
                $this->redirectExit('/');
            }
            $data['error_msg'] = $result;
        }
        if (isset($_SESSION['uid'])) {
            $this->redirectExit('/');
        }
        $this->displayTpl($data, null, false);
    }

    /**
     * 注册接口.
     */
    private function doRegister()
    {
        $keys = array('username', 'password', 'nickname', 'repeat_password', 'job');
        $payloads = MicroUtility::getMultiPost($keys);
        $validator = new ValidateHelper();
        $rules = array(
            array(implode(',', $keys), 'required'), // 必须且非空
            array('username,password,repeat_password', 'useRegex', 'reg' => '/^[a-zA-Z0-9]{5,10}$/'),
            array('nickname', 'useRegex', 'reg' => '/^\p{Han}{1,4}$/u'), //1~4个汉字.
            array('job', 'useRegex', 'reg' => '/^[a-zA-Z]{1,10}(?: [a-z0-9]{1,10})?$/u'), //工作是英文描述,最多允许中间一个空格.
        );
        if ($payloads['password'] != $payloads['repeat_password']) {
            return '用户密码不一致';
        }
        $result = $validator->isValid($payloads, $rules);
        if ($result !== true) {
            return $result[0];
        }
        $user_obj = UserModel::getInstance();
        $exist_user = $user_obj->getOne(array('username' => $payloads['username']));
        if (!empty($exist_user)) {
            return '该用户已经存在!';
        }
        $new_user = $payloads;
        unset($new_user['repeat_password']);
        $index = mt_rand(0, 3);
        $new_user['avatar'] = "/dist/img/avatar{$index}.png";
        $new_user['register_time'] = time();
        $last_insert_id = UserModel::getInstance()->insert($new_user);
        if (!$last_insert_id) {
            return '系统繁忙,注册失败';
        }
        $all_user = $this->user_lst;
        $all_user_ids = array_keys($all_user);
        // 用户注册时候写入所有出现的用户组合可能.
        $all_combination = Utility::combination($all_user_ids);
        $cost_type_obj = CostTypeModel::getInstance();
        foreach ($all_combination as $maybe) {
            $maybe[] = $last_insert_id;
            sort($maybe);
            // 恶心的写法.
            $nickname_lst = array_map(function ($el) use ($all_user) {
                return $all_user[$el]['nickname'];
                    }, $maybe);
            $cost_type_obj->insert(
                array(
                    'who' => implode(',', $maybe),
                    'description' => implode(',', $nickname_lst),
                )
            );
        }
        $_SESSION['uid'] = $last_insert_id;

        return true;
    }

    /**
     * 个人资料.
     */
    public function profile()
    {
        $this->forbidden();
    }
}
