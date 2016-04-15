<?php
/**
 * Created by PhpStorm.
 * User: shellvon
 * Date: 16/4/14
 * Time: 下午3:28.
 */

namespace MicroMan;

defined('APP_ROOT') or exit('should be define constant:APP_ROOT');
defined('APP_DEBUG') or define('APP_DEBUG', false);

/**
 * Class MicroMan.
 */
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

    /**
     * 路由规则.
     * Apache && Nginx都需要有rewrite机制,定向的参数是rt.
     *
     * 使用URL的path以"/"分割,比如domain.com/v1/hello/index
     * 拿么得到的route => ("v1", "hello", "index")
     * 所以Controller名字是helloController,action名字是index,除了最后的俩个元素,就是Controller的路径.
     * 如果分割之后的route是空的,比如domain.com,默认访问 => ("index", "index")
     * 如果分割之后的route是一个元素,比如domain.com/test,默认访问("test", "index")
     */
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

    /**
     * 主函数.执行路由分发,调用对应的controller的action.
     * 调用它之前需要使用AutoLoader加载对应的类文件.
     */
    public function run()
    {
        if (!session_id()) {
            session_start();
        }
        $this->route();
        $tmp = $this->route_path;
        $action_name = array_pop($tmp);
        $controller_name = implode('/', $tmp);
        /*
        $cls_file_name = APP_ROOT.'Controller'.DIRECTORY_SEPARATOR.$controller_name.'Controller.php';
        if (file_exists($cls_file_name)) {
            require_once $cls_file_name;
        } else {
            // error.
        }*/
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

/**
 * Class MicroController.
 */
class MicroController
{
    /**
     * @var MicroTemplate 模版引擎.
     */
    protected $tpl_engine = null;
    /**
     * @var MicroMan Microman引擎,负责路由分发,算是router.
     */
    protected $site_engine = null;
    /**
     * @var string 控制器名字.
     */
    protected $controller_name = null;
    /**
     * @var string 动作名,对应Controller的method.
     */
    protected $action_name = null;
    /**
     * @var array
     */
    protected $site_info = null;

    /**
     * MicroController constructor.
     *
     * @param MicroMan $site_engine
     * @param string   $controller_name
     * @param string   $action_name
     */
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
        $this->tpl_engine->assign('title', isset($this->site_info['site_name']) ? $this->site_info['site_name']: "{$this->controller_name}/{$this->action_name}");
        $this->tpl_engine->assign('site_info', $this->site_info);
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

    public function forbidden()
    {
        // set header code ?
        echo "403 Forbidden";
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
        if (version_compare(PHP_VERSION, '5.4.0', '<')) {
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
     * @param string $json 需要格式输出的json.
     * @param string $pad  字符串.
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
     * 显示模版渲染结果.
     *
     * @param mixed       $data        数据.
     * @param string|null $tpl         模版名.
     * @param bool        $show_header 是否显示Html头部和尾部.
     */
    public function displayTpl($data = null, $tpl = null, $show_header = true)
    {
        if (empty($tpl)) {
            $tpl = "{$this->controller_name}/{$this->action_name}";
        }
        $this->tpl_engine->assign($data);
        if ($show_header) {
            echo $this->tpl_engine->fetch('Header').$this->tpl_engine->fetch($tpl).$this->tpl_engine->fetch('Footer');
        } else {
            $this->tpl_engine->display($tpl);
        }
    }

    /**
     * 是否为ajax请求.
     *
     * @return bool
     */
    public function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strcasecmp('XMLHttpRequest', $_SERVER['HTTP_X_REQUESTED_WITH']) === 0;
    }

    /**
     * 是否为POST请求.
     *
     * @return bool
     */
    public function isPost()
    {
        return strtolower($_SERVER['REQUEST_METHOD']) === 'post';
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

    /**
     * 该方法主要是方便子类进行override从而达到可以直接调用getInstance的目的.
     *
     * @param string $name 类名.
     *
     * @return mixed 对应的类实例.
     */
    protected static function createInstance($name)
    {
        if (!isset(static::$instances[$name])) {
            static::$instances[$name] = new $name();
        }

        return static::$instances[$name];
    }
}

/**
 * 自动的类加载机制.
 * 通过namespace加载对应的类名.
 */
class MicroAutoLoader
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

    /**
     * 添加需要加载的路径,支持链式调用.
     *
     * @param string $path 路径名.
     *
     * @return $this
     */
    public function addPath($path)
    {
        static::$path[] = $path;

        return $this;
    }

    /**
     * 反序列化,for safe.
     */
    private function __wakeup()
    {
        // TODO: Implement __wakeup() method.
    }

    /**
     * 单例,不许克隆.
     */
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    /**
     * 返回对应的实例.
     *
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
     * @param string $name 类名.
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

/**
 * 一个简单明了的PHP原生的模版渲染引擎.
 */
class MicroTemplate
{
    /**
     * @var array 配置信息.
     */
    protected $config = array(
        'template_path' => '',
        'file_extension' => '.tpl.php',
    );

    /**
     * @var array 模版变量.
     */
    protected $tpl_vars = array();

    /**
     * MicroTemplate constructor.
     *
     * @param null $config
     */
    public function __construct($config = null)
    {
        if (!empty($config)) {
            $this->config = array_merge($config, $this->config);
        }
    }

    /**
     * 配置Config.
     *
     * @param array $config
     */
    public function config(array $config)
    {
        $this->config = $config;
    }

    /**
     * 以key,value形式配置某一个config的值.
     *
     * @param $key
     * @param $value
     */
    public function setConfig($key, $value)
    {
        $this->config[$key] = $value;
    }

    /**
     * 获取配置信息.
     * 如果key为null,返回整个配置信息.否则返回对应key的信息(不存在key时返回null).
     *
     * @param mixed $key
     *
     * @return array|null
     */
    public function getConfig($key = null)
    {
        if (empty($key)) {
            return $this->config;
        }

        return isset($this->config[$key]) ? $this->config[$key] : null;
    }

    /**
     * @see http://php.net/manual/en/language.oop5.overloading.php#object.get
     *
     * @param $name
     *
     * @return bool
     */
    public function __get($name)
    {
        return isset($this->tpl_vars[$name]) ? $this->tpl_vars[$name] : null;
    }

    /**
     * @see http://php.net/manual/en/language.oop5.overloading.php#object.set
     *
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->tpl_vars[$name] = $value;
    }

    /**
     * 模版赋值.
     *
     * @param array|string $tpl_var
     * @param mixed        $value
     */
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

    /**
     * 获取模版.
     *
     * @param string $tpl 模版名.
     *
     * @return bool|string 有模版返回file完整目录,否则返回false
     */
    public function getTemplate($tpl)
    {
        $file = $this->config['template_path'].$tpl.$this->config['file_extension'];
        if (file_exists($file) && is_readable($file)) {
            return $file;
        }

        return false;
    }

    /**
     * 输出模版.
     *
     * @param $tpl
     *
     * @throws \Exception
     */
    public function display($tpl)
    {
        echo $this->fetch($tpl);
    }

    /**
     * 解析模版.
     *
     * @param $tpl
     *
     * @return string
     *
     * @throws \Exception
     */
    public function fetch($tpl)
    {
        $result = $this->getTemplate($tpl);
        if (!$result) {
            throw new \Exception("No such tpl file:{$tpl}");
        }
        extract($this->tpl_vars);
        ob_start();
        include_once($result);

        return ob_get_clean();
    }
}

/**
 * 工具包.
 */
class MicroUtility
{
    /**
     * 从$_GET数组中获取对应的值.
     *
     * @param $key
     * @param null $default 默认值.
     *
     * @return mixed 对应的值,木有就返回$default.
     */
    public static function getGet($key, $default = null)
    {
        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }

    /**
     * 从$_POST数组中获取对应的值.
     *
     * @param $key
     * @param null $default
     *
     * @return mixed 对应的值,木有就返回$default.
     */
    public static function getPost($key, $default = null)
    {
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }

    /**
     * 获取多个Key的值.
     *
     * @param array $keys  key数组.
     * @param bool  $assoc 是否返回关联数组.
     *
     * @return array
     */
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
