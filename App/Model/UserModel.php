<?php
/**
 * Created by PhpStorm.
 * User: shellvon
 * Date: 16/4/14
 * Time: 下午9:09.
 */

namespace Model;

use Microman\MicroModel;

/**
 * Class UserModel.
 */
class UserModel extends MicroModel
{
    const TABLE_NAME = 'admin';
    public static function getInstance()
    {
        return parent::createInstance(__CLASS__); // TODO: Change the autogenerated stub
    }

    //TODO:SQL INJECT.
    public function find($username, $password)
    {
        $where = " where username = '{$username}' and password = '{$password}'";
        $real_sql = 'SELECT * FROM '.self::TABLE_NAME.$where;
        $result_set = $this->pdo->query($real_sql)->fetchAll();
        return $result_set;
    }
}
