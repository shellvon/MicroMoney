<?php
/**
 * Created by PhpStorm.
 * User: shellvon
 * Date: 16/4/16
 * Time: 下午7:21.
 */

namespace Controller;

use MicroMan\MicroUtility;
use Model\LogModel;
use Model\NotificationModel;
use Model\UserModel;

class LogsController extends BaseController
{
    /**
     * 查看日志.
     */
    public function view()
    {
        $this->tpl_engine->assign('title', '操作日志');
        $id = MicroUtility::getGet('id');
        if (ctype_digit($id) && $id != 0) {
            $condition = array('id' => $id);
        } else {
            $condition = null;
        }
        $records = LogModel::getInstance()->getAll($condition);
        $cache = array();
        foreach ($records as &$el) {
            $operator = $el['operator_id'];
            if (isset($cache[$operator])) {
                $user = $cache[$operator];
            } else {
                $user = UserModel::getInstance()->getOne(array('id' => $el['operator_id']));
                $cache[$operator] = $user;
            }
            $el['operator_name'] = $user['nickname'];
        }
        $response = array(
            'action_map' => NotificationModel::$ALL_ACTION_MAP,
            'operation_logs' => $records,
        );
        $this->displayTpl($response);
    }

    /**
     * 删除日志.
     */
    public function ajaxDelete()
    {
        $id = MicroUtility::getPost('id');
        if (ctype_digit($id) && $id != 0) {
            $this->displaySuccessJson(null, '删除成功');
        } else {
            $this->displayErrorJson('参数错误');
        }
    }
}
