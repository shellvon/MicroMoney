<?php
/**
 * Created by PhpStorm.
 * User: shellvon
 * Date: 16/4/14
 * Time: 下午3:25.
 */
require_once 'Config/Config.php';
require_once '../Framework/MicroMan.php';

//fixed my session bug.
session_save_path('/tmp');

$site_info = array(
    'template_path' => implode(DIRECTORY_SEPARATOR, array(APP_ROOT, 'View', 'Tpl')).DIRECTORY_SEPARATOR,
    'site_name' => 'MicroMoney',
    'static_resource_path' => '/Public/static',
);

\MicroMan\MicroAutoLoader::getInstance()->addPath(APP_ROOT)->initialize();
$app = new \MicroMan\MicroMan();
$app->setSiteInfo($site_info)->run();
