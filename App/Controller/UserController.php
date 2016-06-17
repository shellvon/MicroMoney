<?php
/**
 * Created by PhpStorm.
 * User: shellvon
 * Date: 16/4/19
 * Time: 下午3:24.
 */

namespace Controller;

use Model\CostTypeModel;
use Model\NotificationModel;
use Model\UserModel;
use MicroMan\MicroUtility;
use Model\UserNotifyModel;
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
            array('job', 'useRegex', 'reg' => '/^[a-zA-Z]{1,10}(?: [a-z0-9]{1,10})?$/ui'), //工作是英文描述,最多允许中间一个空格.
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
        $cost_type_obj = CostTypeModel::getInstance();
        if (empty($this->type_map)) {
            // 第一次注册,消费类型可以为一个人,之后都不允许一个人的情况.
            $cost_type_obj->insert(
                array(
                    'who' => $last_insert_id,
                    'description' => $new_user['nickname'],
                )
            );
        } else {
            foreach ($this->type_map as $el) {
                $cost_type_obj->insert(
                    array(
                        'who' => $el['who'].','.$last_insert_id,
                        'description' => $el['description'].','.$new_user['nickname'],
                    )
                );
            }
        }
        $this->sendRegisterMsgToAllUser($last_insert_id);

        $_SESSION['uid'] = $last_insert_id;

        return true;
    }

    /**
     * @param $who
     */
    private function sendRegisterMsgToAllUser($who)
    {
        $notify_id = NotificationModel::getInstance()->createNotification(
            $who,
            '注册为新用户',
            NotificationModel::ACTION_REGISTER,
            NotificationModel::NOTIFICATION_TYPE_SYS_MESSAGE);
        $all_user = $this->user_lst;
        $all_user_ids = array_keys($all_user);
        $all_user_ids[] = $who; // 通知自己.
        foreach ($all_user_ids as $id) {
            UserNotifyModel::getInstance()->createNotify($notify_id, $id);
        }
    }

    /**
     * 检查用户名之类的是否已经存在.
     */
    public function ajaxExists()
    {
        $nickname = MicroUtility::getGet('nickname');
        $username = MicroUtility::getGet('username');

        // TODO: sql inject.
        if (!empty($nickname)) {
            $type = '昵称';
            $condition = array('nickname' => $nickname);
        } elseif (!empty($username)) {
            $type = '用户名';
            $condition = array('username' => $username);
        } else {
            $this->displayErrorJson('用户名/昵称不能为空', 2);
        }
        $exists = UserModel::getInstance()->getOne($condition);
        if ($exists) {
            $this->displayErrorJson('该'.$type.'已存在', 1);
        } else {
            $this->displaySuccessJson('该'.$type.'可用');
        }
    }

    /**
     * 获取系统通知.
     * 比如用户注册信息.
     */
    public function ajaxMessage()
    {
        $uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : 0;
        $message = UserNotifyModel::getInstance()->getSysMessage($uid);
        $size = count($message);
        $data = array(
            'count' => $size,
            'list' => $message,
        );
        $this->displaySuccessJson($data);
    }

    /**
     * 获取操作消息.
     */
    public function ajaxRemind()
    {
        $uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : 0;
        $reminds = UserNotifyModel::getInstance()->getRemind($uid);
        $size = count($reminds);
        $data = array(
            'count' => $size,
            'list' => $reminds,
        );
        $this->displaySuccessJson($data);
    }

    /**
     * 将未读消息设置为已读.
     */
    public function ajaxRead()
    {
        $uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : 0;
        $notify_id_str = MicroUtility::getPost('notify_ids');
        $regex = '/^\d+(?:,\d+)*$/u';
        if (!preg_match($regex, $notify_id_str) || $uid == 0) {
            $this->displayErrorJson('参数异常');
        }
        $condition = array(
            'receiver' => $uid,
            'notify_id' => explode(',', $notify_id_str),
        );
        UserNotifyModel::getInstance()->update(array('is_read' => 1), $condition);
        $this->displaySuccessJson('操作成功');
    }

    /**
     * 用户资料.
     */
    public function ajaxProfile()
    {
        $uid = MicroUtility::getGet('uid');
        if (!ctype_digit($uid) || $uid == 0) {
            $this->displayErrorJson('参数错误');
        }
        $user = UserModel::getInstance()->getOne(array('id' => $uid), 'job, avatar, register_time, nickname');
        if (empty($user)) {
            $this->displayErrorJson('无此用户');
        }
        $user['reg_time'] = date('F, Y', $user['register_time']);
        unset($user['register_time']);
        $this->displaySuccessJson($user);
    }
}
