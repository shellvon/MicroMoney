<?php
/**
 * Created by PhpStorm.
 * User: shellvon
 * Date: 16/4/14
 * Time: 下午3:28.
 */

namespace Microman;

defined('APP_ROOT') or exit('should be define constant:APP_ROOT');
defined('APP_DEBUG') or define('APP_DEBUG', false);

class MicroMan
{
    protected $route_map = array();
    protected $route_path = array();
    protected $site_info;
    protected $controller;
    protected $action;
    protected $default_route = 'index';

    public function setSiteInfo($site_info)
    {
        $this->site_info = $site_info;

        return $this;
    }

    public function getSiteInfo()
    {
        return $this->site_info;
    }
    private function route()
    {
        $rp = empty($_GET['rt']) ? '' : $_GET['rt'];
        unset($_GET['rt']);
        if (0 === strpos($rp, '/') && !empty($rp)) {
            $rp = substr($rp, 1);
        }
        $this->route_path = explode('/', $rp);
        if (end($this->route_path) == '') {
            array_pop($this->route_path);
        }
        $size = count($this->route_path);
        if (0 === $size) {
            $this->route_path = array($this->default_route, $this->default_route);
        } elseif (1 === $size) {
            $this->route_path[] = $this->default_route;
        }
        if (!preg_match('/^\w*$/', str_replace('/', '_', $rp))) {
            die('illegal access!');
        }
        $this->route_path = array_map(function ($name) {
            return ucfirst(strtolower($name));
        }, $this->route_path);
    }

    public function nationConvert($name, $to_camel = false)
    {
        if ($to_camel) {
            return strtolower(trim(preg_replace('/[A-Z]/', '_\\0', $name), '_'));
        } else {
            return ucfirst(preg_replace('/_([a-zA-Z])/e', "strtoupper('\\1')", $name));
        }
    }

    public function run()
    {
        if (!session_id()) {
            session_start();
        }
        $this->route();
        $tmp = $this->route_path;
        $action_name = array_pop($tmp);
        $controller_name = implode('/', $tmp);
        $cls_file_name = APP_ROOT.'Controller'.DIRECTORY_SEPARATOR.$controller_name.'Controller.php';
        if (file_exists($cls_file_name)) {
            require_once $cls_file_name;
        } else {
            // error.
        }
        $class_name = "\\Controller\\{$controller_name}Controller";
        $controller = new $class_name($this, ucfirst($controller_name), ucfirst($action_name));
        $controller->before();
        if (method_exists($controller, $action_name)) {
            $controller->$action_name();
        } else {
            $controller->page404();
        }
    }
}

class MicroController
{
    /**
     * @var MicroTemplate
     */
    protected $tpl_engine = null;
    /**
     * @var MicroMan
     */
    protected $site_engine = null;
    protected $controller_name = null;
    protected $action_name = null;
    protected $site_info = null;
    public function __construct($site_engine, $controller_name, $action_name)
    {
        $this->site_engine = $site_engine;
        $this->site_info = $this->site_engine->getSiteInfo();
        $this->controller_name = $controller_name;
        $this->action_name = $action_name;
        $this->initialize();
    }

    /**
     * 做一些初始化的工作.
     */
    protected function initialize()
    {
        $tpl_path = isset($this->site_info['template_path']) ? $this->site_info['template_path'] : null;
        $this->tpl_engine = new MicroTemplate();
        if ($tpl_path != null) {
            $this->tpl_engine->setConfig('template_path', $tpl_path);
        }
        $this->tpl_engine->assign('site_name', $this->site_info['site_name']);
        $this->tpl_engine->assign('static_path', $this->site_info['static_resource_path']);
    }

    /**
     * 在所有action之前调用.比如权限检查.
     */
    public function before()
    {
    }

    /**
     * 404页面.
     */
    public function page404()
    {
        echo "<h1 style='text-align: center'> 404 Page Not Found</h1>";
    }

    /**
     * 重定向.
     *
     * @param $url
     * @param int $code
     */
    public function redirectExit($url, $code = 302)
    {
        header('Location: '.$url, true, $code);
        exit(0);
    }

    /**
     * 以JSON格式输出.
     *
     * @param array $data              需要输出的数据.
     * @param bool  $unescaped_unicode 是否转义unicode.
     */
    public function displayJson(array $data, $unescaped_unicode = false)
    {
        if (version_compare(PHP_VERSION, '5.9.0', '<')) {
            if ($unescaped_unicode) {
                $msg = preg_replace_callback('/\\\\u([0-9a-f]{4})/i',
                    function ($matches) {
                        return mb_convert_encoding(pack('H*', $matches[1]), 'UTF-8', 'UTF-16');
                    }, json_encode($data));
                $msg = str_replace('\\/', '/', $msg);
            } else {
                $msg = str_replace('\\/', '/', json_encode($data));
            }
            if (APP_DEBUG) {
                $msg = $this->jsonPrettyPrint($msg, '    ');
            }
        } else {
            $flag = JSON_UNESCAPED_SLASHES;
            if ($unescaped_unicode) {
                $flag |= JSON_UNESCAPED_UNICODE;
            }
            if (APP_DEBUG) {
                $flag |= JSON_PRETTY_PRINT;
            }
            $msg = json_encode($data, $flag);
        }
        header('Content-Type:application/json; charset=utf-8');
        echo $msg;
    }

    /**
     * 类似于Python的pprint,把JSON数据输出好看一点,helpful when i debug.
     *
     * @see http://ryanuber.com/07-10-2012/json-pretty-print-pre-5.4.html
     *
     * @param $json 需要格式输出的json.
     * @param string $istr repeat_str.
     *
     * @return string
     */
    private function jsonPrettyPrint($json, $pad = '')
    {
        $result = '';
        for ($p = $q = $i = 0; isset($json[$p]); ++$p) {
            $json[$p] == '"' && ($p > 0 ? $json[$p - 1] : '') != '\\' && $q = !$q;
            if (strchr('}]', $json[$p]) && !$q && $i--) {
                strchr('{[', $json[$p - 1]) || $result .= "\n".str_repeat($pad, $i);
            }
            $result .= $json[$p];
            if (strchr(',{[', $json[$p]) && !$q) {
                $i += strchr('{[', $json[$p]) === false ? 0 : 1;
                strchr('}]', $json[$p + 1]) || $result .= "\n".str_repeat($pad, $i);
            }
        }

        return $result;
    }

    /**
     * 显示模版信息.
     *
     * @param mixed       $data
     * @param string|null $tpl
     * @param bool        $show_header
     */
    public function displayTpl($data = null, $tpl = null, $show_header = false)
    {
        if (empty($tpl)) {
            $tpl = "{$this->controller_name}/{$this->action_name}";
        }
        $this->tpl_engine->assign($data);
        if ($show_header) {
            echo $this->tpl_engine->fetch('header').$this->tpl_engine->fetch($tpl).$this->displayTpl('footer');
        } else {
            $this->tpl_engine->display($tpl);
        }
    }

    public function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strcasecmp('XMLHttpRequest', $_SERVER['HTTP_X_REQUESTED_WITH']) === 0;
    }

    public function isPost()
    {
        return isset($_POST) && !empty($_POST);
    }
}

/**
 * Class MicroModel.
 */
class MicroModel
{
    /**
     * @var \PDO
     */
    protected $pdo = null;

    protected static $instances = array();

    /**
     * MicroModel constructor.
     */
    public function __construct()
    {
        $this->buildDatabaseConn();
    }

    /**
     * Model里面建立数据库连接.Ugly.
     */
    protected function buildDatabaseConn()
    {
        try {
            $options = array(\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING);
            $this->pdo = new \PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS, $options);
        } catch (\PDOException $ex) {
            echo 'db connection established failed';
            if (APP_DEBUG) {
                echo $ex->getMessage();
            }
            exit(1);
        }
    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        return static::createInstance(__CLASS__);
    }

    protected static function createInstance($name)
    {
        if (!isset(static::$instances[$name])) {
            static::$instances[$name] = new $name();
        }

        return static::$instances[$name];
    }
}

class AutoLoader
{
    private static $instance;
    protected static $path = array();

    private function __construct()
    {
        // 默认看App目录.
        static::$path = array(
            __DIR__.'/../App/',
        );
    }

    public function addPath($path)
    {
        static::$path[] = $path;

        return $this;
    }

    private function __wakeup()
    {
        // TODO: Implement __wakeup() method.
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (!static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * 通过命名空间自动加载.
     *
     * @param $name
     *
     * @return bool
     */
    private function autoloadByNamespace($name)
    {
        $class_path = str_replace('\\', DIRECTORY_SEPARATOR, $name);
        foreach (static::$path as $k => $root) {
            $file = $root.$class_path.'.php';
            //echo $file . PHP_EOL;
            if (is_file($file)) {
                require_once $file;
                if (class_exists($name, false)) {
                    return true;
                }
            }
        }
    }

    /**
     * 初始化自动加载函数.
     */
    public function initialize()
    {
        spl_autoload_register(array($this, 'autoloadByNamespace'));
    }
}

class MicroTemplate
{
    protected $config = array(
        'template_path' => '',
        'file_extension' => '.tpl.php',
    );

    protected $tpl_vars = array();

    public function __construct($config = null)
    {
        if (!empty($config)) {
            $this->config = array_merge($config, $this->config);
        }
    }

    public function config(array $config)
    {
        $this->config = $config;
    }

    public function setConfig($key, $value)
    {
        $this->config[$key] = $value;
    }

    public function getConfig($key = null)
    {
        if (empty($key)) {
            return $this->config;
        }

        return isset($this->config[$key]) ? $this->config[$key] : null;
    }

    public function __get($name)
    {
        return isset($this->tpl_vars[$name]) ? $this->tpl_vars[$name] : false;
    }

    public function __set($name, $value)
    {
        $this->tpl_vars[$name] = $value;
    }

    public function assign($tpl_var, $value = null)
    {
        if (is_array($tpl_var)) {
            foreach ($tpl_var as $_key => $_val) {
                if ($_key != '') {
                    $this->{$_key} = $_val;
                }
            }
        } else {
            if ($tpl_var != '') {
                $this->{$tpl_var} = $value;
            }
        }
    }

    public function template($tpl)
    {
        $file = $this->config['template_path'].$tpl.$this->config['file_extension'];
        if (file_exists($file) && is_readable($file)) {
            return $file;
        }

        return false;
    }

    public function display($tpl)
    {
        echo $this->fetch($tpl);
    }

    public function fetch($tpl)
    {
        $result = $this->template($tpl);
        if (!$result) {
            throw new \Exception("No such tpl file:{$tpl}");
        }
        extract($this->tpl_vars);
        ob_start();
        include_once $result;

        return ob_get_clean();
    }
}

/**
 * Class MicroUtility.
 */
class MicroUtility
{
    public static function getGet($key, $default = null)
    {
        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }

    public static function getPost($key, $default = null)
    {
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }

    public static function getMultiPost(array $keys, $assoc = true)
    {
        $result = array();
        foreach ($keys as $key) {
            if ($assoc) {
                $result[$key] = static::getPost($key);
            } else {
                $result[] = static::getPost($key);
            }
        }

        return $result;
    }
}
