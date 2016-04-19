<?php
/**
 * Created by PhpStorm.
 * User: shellvon
 * Date: 16/4/16
 * Time: 下午7:21.
 */

namespace Controller;

class LogsController extends BaseController
{
    /**
     * 查看日志.
     */
    public function view()
    {
        $this->tpl_engine->assign('title', '操作日志');
        $this->displayTpl();
    }
}
