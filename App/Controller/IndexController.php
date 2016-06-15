<?php
/**
 * Created by PhpStorm.
 * User: shellvon
 * Date: 16/4/14
 * Time: 下午5:13.
 */

namespace Controller;

use MicroMan\MicroDatabase;
use MicroMan\MicroUtility;
use Model\CostTypeModel;
use Model\LogModel;
use Model\NotificationModel;
use Model\RecordModel;
use Model\UserNotifyModel;
use Utility\ValidateHelper;
use Model\UserModel;

class IndexController extends BaseController
{
    /**
     * 主页.
     */
    public function index()
    {
        $record_obj = RecordModel::getInstance();
        $table = $record_obj->getTableName();
        //数据库中记录的每个人的消费,每种类型的消费
        $each_user_cost = $record_obj->query("select paid_uid, sum(cost) as cost from {$table} where is_deal=0 and is_delete = 0 group by paid_uid");
        $each_type_cost = $record_obj->query("select `type`, sum(cost) as `cost` from {$table} where is_deal = 0 and is_delete = 0 group by `type`");
        //总的消费记录
        $total_cost = array_sum(array_map(function ($data) {return $data['cost'];}, $each_user_cost));
        $total_cost = $total_cost ? $total_cost : 1;

        //初始化用户信息.
        $user_data = array();
        foreach ($this->user_lst as $uid => $user) {
            $user_data[$uid] = array(
                'nickname' => $user['nickname'],
                'percent' => 0,
                'cost' => 0,
                'benefit' => 0,
            );
        }

        //个人暂未结算金额比例计算,为了保证总和100%，此处最后一个的计算使用100-其他。
        for ($cnt = count($each_user_cost), $i = 0, $percent_cnt = 0; $i < $cnt; ++$i) {
            $uid = $each_user_cost[$i]['paid_uid'];
            $percent = 100 * number_format($each_user_cost[$i]['cost'] / $total_cost, 4, '.', '');
            if ($i == $cnt - 1) {
                $percent = 100 - $percent_cnt;
            }
            $percent_cnt += $percent;
            $user_data[$uid] = array(
                'nickname' => $this->user_lst[$uid]['nickname'],
                'percent' => $percent,
                'cost' => number_format($each_user_cost[$i]['cost'], 2, '.', ''),
                'benefit' => 0,
            );
        }
        //各类型消费统计
        for ($cnt = count($each_type_cost), $i = 0, $percent_cnt = 0;$i < $cnt;++$i) {
            $percent = 100 * number_format($each_type_cost[$i]['cost'] / $total_cost, 4, '.', '');
            $type_info = $this->type_map[$each_type_cost[$i]['type']];
            $who_arr = explode(',', $type_info['who']);
            $avg_cost = $each_type_cost[$i]['cost'] / count($who_arr);
            foreach ($who_arr as $who) {
                $user_data[$who]['benefit'] += $avg_cost;
            }
            $each_type_cost[$i]['description'] = $type_info['description'];
            if ($i == $cnt - 1) {
                $percent = 100 - $percent_cnt;
            }
            $percent_cnt += $percent;
            $each_type_cost[$i]['percent'] = $percent;
            $each_type_cost[$i]['cost'] = number_format($each_type_cost[$i]['cost'], 2, '.', '');
        }
        $current_uid = $this->user_info['id'];
        $current_user_cost = array(
            'cost' => number_format($user_data[$current_uid]['benefit'], 2, '.', ''),
            'settlement' => number_format($user_data[$current_uid]['cost'] - $user_data[$current_uid]['benefit'], 2, '.', ''),
        );
        $user_info = array_merge($this->user_info, $current_user_cost);
        $records = $record_obj->getAll(array('is_delete' => 0, 'id, paid_uid, cost, paid_day, type, description, operator_uid, create_time, update_time, is_deal'));

        $response = array(
            'user_info' => $user_info,
            'each_type_cost' => $each_type_cost,
            'user_data' => $user_data,
            'records' => $records,
        );
        $this->displayTpl($response);
    }

    /**
     * 搜索接口.
     */
    public function search()
    {
        $query = MicroUtility::getGet('q');
        // search what ?
        $this->forbidden();
    }

    /**
     * ajax用来增加/修改/结算的API.
     */
    public function command()
    {
        if (!$this->isPost()) {
            $this->forbidden();
        }
        $action = MicroUtility::getPost('action');
        switch (strtolower($action)) {
            case 'add':
                $this->addCommand();
                break;
            case 'update':
                $this->updateCommand();
                break;
            case 'deal':
                $this->dealCommand();
                break;
            case 'dealbatch':
                $this->dealBatchCommand();
                break;
            default:
                $this->displayErrorJson('不合法的操作');
        }
    }

    /**
     * 添加记录.
     */
    private function addCommand()
    {
        $payloads = $this->parseInsertOrUpdateParams(false);
        // http://stackoverflow.com/questions/2374631/pdoparam-for-dates
        // 如果timestamp字段得以字符串的形式传入,为了简单,所以我把timestamp的格式直接以int形式存取.
        $new_data = array(
            'operator_uid' => $_SESSION['uid'],
            'create_time' => time(),
            'update_time' => time(),
        );
        // 组装成为DB中的字段,以方便数据插入DB.
        $new_data = array_merge($new_data, $payloads);
        $last_insert_id = RecordModel::getInstance()->insert($new_data, MicroDatabase::INSERT_IN_DUP_NONE);
        if ($last_insert_id) {
            // add operation log.
            $content = '添加了一个'.$new_data['cost'].'元的交易,ID为:'.$last_insert_id;
            $action = NotificationModel::ACTION_INSERT_DEAL;
            $this->sendRemindToAllUser($action, $content);
            LogModel::getInstance()->addLogs($action, $_SESSION['uid'], $new_data);
            $this->displaySuccessJson(null, '添加成功');
        } else {
            $this->displayErrorJson('添加失败!');
        }
    }

    /**
     * 通知用户操作结果.
     *
     * @param $action
     * @param $content
     */
    private function sendRemindToAllUser($action, $content)
    {
        $notify_id = NotificationModel::getInstance()->createNotification(
            $_SESSION['uid'],
            $content,
            $action,
            NotificationModel::NOTIFICATION_TYPE_REMIND
        );
        $all_user = $this->user_lst;
        $all_user_ids = array_keys($all_user);
        foreach ($all_user_ids as $id) {
            UserNotifyModel::getInstance()->createNotify($notify_id, $id);
        }
    }

    /**
     * 处理@insertCommand和@updateCommand需要用到的参数.
     *
     * 如果参数$check_id为false,则不进行检查ID,此时参数代表的是insert.
     * 如果参数$check_id为true,则进行检查ID,此时参数代码的是update.
     *
     * @param bool $need_id 是否需要ID.
     *
     * @return array|bool
     */
    private function parseInsertOrUpdateParams($need_id = false)
    {
        $keys = array('paid_uid', 'cost', 'paid_day', 'type', 'description');
        if ($need_id) {
            $keys[] = 'id';
        }
        $payloads = MicroUtility::getMultiPost($keys);
        $validator = new ValidateHelper();
        $all_user_id = UserModel::getInstance()->getAll(null, 'id', 'id');
        $all_type_id = CostTypeModel::getInstance()->getAll(null, 'id', 'id');
        $allowed_type_id_arr = array_keys($all_type_id);
        $allowed_paid_id_arr = array_keys($all_user_id);
        //TODO:成员in_array检查不严谨 @see http://php.net/manual/zh/function.in-array.php#106319
        $rules = array(
            array(implode(',', $keys), 'required'), // 必须且非空
            array('paid_day', 'date'),
            array('cost', 'float'),
            array('paid_uid', 'enums', 'haystack' => $allowed_paid_id_arr),
            array('type', 'enums', 'haystack' => $allowed_type_id_arr),
        );
        //如果需要ID,需要加上ID参数的检查.
        if ($need_id) {
            $rules[] = array('id', 'required');
            $rules[] = array('id', 'integer');
        }
        $result = $validator->isValid($payloads, $rules);
        if ($result !== true) {
            $this->displayErrorJson($result[0]);
        }
        $payloads['description'] = htmlspecialchars($payloads['description']);

        return $payloads;
    }

    /**
     * 更新记录.
     */
    private function updateCommand()
    {
        $payloads = $this->parseInsertOrUpdateParams(true);
        $id = $payloads['id'];
        $params = $payloads;
        unset($params['id']);
        $params['update_time'] = time();
        $condition = array(
            'id' => $id, // 更新指定的记录ID.
            'is_delete' => 0, // 没有删除的数据.
            'is_deal' => 0, // 没有结算处理的数据.
        );
        $old_data =  RecordModel::getInstance()->getOne($condition);
        $result = RecordModel::getInstance()->update($params, $condition);
        if ($result === false) {
            $this->displayErrorJson('更新失败!');
        } else {
            // add operation log.
            $content = '更新了一个deal';
            $action = NotificationModel::ACTION_UPDATE_DEAL;
            LogModel::getInstance()->addLogs($action, $_SESSION['uid'], $params, $old_data);
            $this->sendRemindToAllUser($action, $content);
            $this->displaySuccessJson(null, '更新成功');
        }
    }

    /**
     * 单个处理.
     */
    private function dealCommand()
    {
        $id = MicroUtility::getPost('id');
        if (empty($id) || !is_numeric($id)) {
            die(json_encode(array('error' => 1, 'msg' => '参数不合法')));
        }
        $result = RecordModel::getInstance()->update(array('is_deal' => 1), array('id' => $id, 'is_delete' => 0));
        if ($result === false) {
            $this->displayErrorJson('处理结算失败!');
        } else {
            // add operation log.
            $content = '结算了一个deal';
            $action = NotificationModel::ACTION_SINGLE_DEAL;
            $this->sendRemindToAllUser($action, $content);
            LogModel::getInstance()->addLogs($action, $_SESSION['uid'], array('id' => $id));
            $this->displaySuccessJson(null, '处理结算成功');
        }
    }

    /**
     * 批量处理.
     */
    private function dealBatchCommand()
    {
        $old_data = RecordModel::getInstance()->getAll(array('is_deal' => 0, 'is_delete' => 0));
        $result = RecordModel::getInstance()->update(array('is_deal' => 1), array('is_delete' => 0));
        if ($result === false) {
            $this->displayErrorJson('全部结算失败!');
        } else {
            // add operation log.
            $content = '批量结算deal';
            $action = NotificationModel::ACTION_BATCH_DEAL;
            $this->sendRemindToAllUser($action, $content);
            LogModel::getInstance()->addLogs($action, $_SESSION['uid'], null, $old_data);
            $this->displaySuccessJson(null, '全部结算成功');
        }
    }
}
