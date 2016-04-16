<?php
/**
 * Created by PhpStorm.
 * User: shellvon
 * Date: 16/4/16
 * Time: 下午7:21.
 */

namespace Controller;

use MicroMan\MicroController;

class LogsController extends MicroController
{
    /**
     * 查看日志.
     */
    public function view()
    {
        $this->page404();
    }
}
