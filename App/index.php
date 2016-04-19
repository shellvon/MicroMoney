<?php
/**
 * Created by PhpStorm.
 * User: shellvon
 * Date: 16/4/14
 * Time: 下午3:25.
 */
require_once 'Config/Config.php';
require_once '../MicroMan/MicroMan.php';

//fixed my session bug.
session_save_path('/tmp');
ini_set('date.timezone', 'Asia/Chongqing');
$site_info = array(
    'template_path' => implode(DIRECTORY_SEPARATOR, array(APP_ROOT, 'View', 'Tpl')).DIRECTORY_SEPARATOR,
    'site_name' => 'MicroMoney',
    'static_resource_path' => '/Public/static',
    'site_logo' => array(
        'mini' => '<b>钱</b>呢',
        'large' => '<b>钱呢</b>去哪里呀',
    ),
);

\MicroMan\MicroAutoLoader::getInstance()->addPath(APP_ROOT)->initialize();
$app = new \MicroMan\MicroMan();
$app->setSiteInfo($site_info)->run();
