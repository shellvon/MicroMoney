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
        $this->tpl_engine->assign('title', isset($this->site_info['site_name']) ? $this->site_info['site_name'] : "{$this->controller_name}/{$this->action_name}");
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
        echo '403 Forbidden';
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
    const TABLE_NAME = '';
    protected $db_engine = null;

    protected static $instances = array();

    /**
     * MicroModel constructor.
     */
    public function __construct()
    {
        $this->db_engine = MicroDatabase::getInstance();
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

    public function getTableName()
    {
        return self::TABLE_NAME;
    }

    public function getOne($condition = null, $fields = null)
    {
        return $this->db_engine->select($this->getTableName(), $fields, $condition, 1);
    }

    public function getAll($condition = null, $fields = null, $assoc_key = null)
    {
        $result = $this->db_engine->select($this->getTableName(), $fields, $condition);
        if (!empty($assoc_key)) {
            $arr = array();
            foreach ($result as $row) {
                $arr[$row[$assoc_key]] = $row;
            }

            return $arr;
        }

        return $result;
    }
}

/**
 * 自动的类加载机制.
 * 通过namespace加载对应的类名.
 */
class MicroAutoLoader
{
    private static $instance = null;
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

    public function setConfig($k, $v = null)
    {
        if (is_array($k)) {
            foreach ($k as $key => $value) {
                $this->config[$key] = $value;
            }
        } else {
            $this->config[$k] = $v;
        }

        return $this;
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
        include_once $result;

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

/**
 * 数据库相关.灵感来自medoo => http://medoo.in.
 */
class MicroDatabase
{
    /**
     * @var \PDO
     */
    protected $pdo = null;

    protected static $instance = null;

    protected $config = null;

    protected $connected = false;

    protected $last_sql = '';

    const INSERT_IN_DUP_NONE = 0;
    const INSERT_ON_DUP_IGNORE = 1;
    const INSERT_ON_DUP_UPDATE = 2;

    private function __construct()
    {
        // default configuration.
        $this->config = array(
            'db_type' => DB_TYPE,
            'db_host' => DB_HOST,
            'db_name' => DB_NAME,
            'db_user' => DB_USER,
            'db_pass' => DB_PASS,
            'db_port' => defined(DB_PORT) ? DB_PORT : 3306,
            'charset' => 'UTF8',
        );
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
     * 修改配置,如果$k是数组,循环设置里面的key,value
     * 否则以$k为键,$v为对应的配置值.
     *
     * @param string|array $k
     * @param mixed        $v
     *
     * @return $this
     */
    public function setConfig($k, $v = null)
    {
        if (is_array($k)) {
            foreach ($k as $key => $value) {
                $this->config[$key] = $value;
            }
        } else {
            $this->config[$k] = $v;
        }

        return $this;
    }

    /**
     * 获取配置信息.如果$k为空,返回当前所有配置信息.否则返回对应$k的信息.
     *
     * @param string|null $k
     *
     * @return mixed
     */
    public function getConfig($k = null)
    {
        if (is_null($k)) {
            return $this->config;
        }

        return isset($this->config[$k]) ? $this->config[$k] : null;
    }

    /**
     * 建立数据库连接.
     *
     * @throws \Exception
     */
    protected function buildDatabaseConn()
    {
        $db_type = isset($this->config['db_type']) ? $this->config['db_type'] : 'mysql';
        $db_host = isset($this->config['db_host']) ? $this->config['db_host'] : '127.0.0.1';
        $username = isset($this->config['db_user']) ? $this->config['db_user'] : null;
        $password = isset($this->config['db_pass']) ? $this->config['db_pass'] : null;
        $db_port = isset($this->config['db_port']) ? $this->config['db_port'] : 3306;

        // see => http://php.net/manual/en/pdo.construct.php#113498
        $charset = isset($this->config['charset']) ? $this->config['charset'] : 'UTF8';

        /*
         * A key=>value array of driver-specific connection options.
         * http://php.net/manual/en/pdo.drivers.php
         */
        $options = isset($this->config['options']) ? $this->config['options'] : null;
        if (!static::isSupported($db_type)) {
            throw new \Exception("sorry, database type:{$db_type} not supported in your php env");
        }
        $dsn = '';
        switch (strtolower($db_type)) {
            case 'sqlite':
                // there, $host is the database absolute file path
                // @see => http://php.net/manual/zh/ref.pdo-sqlite.connection.php
                $dsn = $db_type.':'.$this->config['host'];
                break;
            case 'mysql':
                $dsn = $db_type.':host='.$db_host.(empty($db_port) ? ';port='.$db_port : '').';dbname='.$this->config['db_name'].';charset='.$charset;
                break;
            default:
                break;
        }
        $this->pdo = new \PDO($dsn, $username, $password, $options);
    }

    /**
     * 连接DB,如果已经连过了,什么也不做.
     *
     * @throws \Exception
     */
    public function connect()
    {
        if (!$this->connected) {
            $this->buildDatabaseConn();
            $this->connected = true;
        }
    }

    /**
     * 判断给定的数据库类型平台是否支持.
     *
     * @see http://php.net/manual/zh/pdo.getavailabledrivers.php
     *
     * @param $db_type
     *
     * @return bool
     */
    public static function isSupported($db_type)
    {
        return in_array($db_type, \PDO::getAvailableDrivers());
    }

    /**
     * 查询语句.
     *
     * @param string $table     表名
     * @param mixed  $fields    查询的字段.
     * @param array  $condition 查询条件,关联数组.可选.
     * @param int    $limit     返回的记录数,默认所有.
     * @param int    $start     记录开始,用于limit, zero-based.
     * @param string $order_by  排序依赖.
     *
     * @return array 关联数组.
     */
    public function select($table, $fields = null, $condition = null, $limit = null, $start = null, $order_by = null)
    {
        if (!$this->connected) {
            $this->connect();
        }
        $real_sql = 'SELECT '.$this->getFields($fields).' FROM '.$table.' WHERE '.$this->buildWhere($condition);
        if (!empty($order_by)) {
            $real_sql .= ' ORDER BY '.$order_by;
        }
        if (!is_null($limit)) {
            $real_sql .= ' LIMIT '.(!is_null($start) ? "$start, " : '')."$limit";
        }
        $pstmt = $this->pdo->prepare($real_sql);
        $this->bindValue($pstmt, $condition);
        $this->last_sql = $pstmt->queryString;
        $pstmt->execute();
        if (!is_null($limit) && $limit == 1) {
            return $pstmt->fetch(\PDO::FETCH_ASSOC);
        } else {
            return $pstmt->fetchAll(\PDO::FETCH_ASSOC);
        }
    }

    /**
     * 更新语句.
     *
     * @param string $table     表名.
     * @param array  $params    参数,关联数组.
     * @param array  $condition 更新条件,关联数组.
     *
     * @return bool|int 成功返回更新条数,否则返回false
     */
    public function update($table, $params, $condition)
    {
        $param_prefix = 'param_';
        $where_prefix = 'where_';
        $set_string = $this->buildSetParams($params, $param_prefix);
        $where_string = $this->buildWhere($condition, 'AND', $where_prefix);
        $real_sql = 'UPDATE '.$table.' SET '.$set_string.' WHERE '.$where_string;
        $pstmt = $this->pdo->prepare($real_sql);
        $this->bindValue($pstmt, $params, $param_prefix);
        $this->bindValue($pstmt, $condition, $where_prefix);
        $this->last_sql = $pstmt->queryString;
        $result = $pstmt->execute();

        return $result ? $pstmt->rowCount() : false;
    }

    /**
     * @param \PDOStatement $pstmt
     * @param $data
     * @param string $prefix
     *
     * @return MicroDatabase
     */
    private function bindValue(&$pstmt, $data, $prefix = '')
    {
        foreach ((array) $data as $key => $val) {
            $pstmt->bindValue(':'.$prefix.$key, $val);
        }

        return $this;
    }

    /**
     * 插入语句.
     *
     * @see http://stackoverflow.com/questions/548541/insert-ignore-vs-insert-on-duplicate-key-update
     *
     * @param string $table  表名.
     * @param array  $params 插入数据.
     * @param int    $on_dup 插入方式.
     *
     * @return int Last insert Id.
     */
    public function insert($table, $params, $on_dup = self::INSERT_IN_DUP_NONE)
    {
        $columns = '';
        $values = '';
        $updates = array();
        foreach ($params as $column => $value) {
            $columns .= "{$column},";
            $values .=  ":{$column},";
            $updates[] = $column.'='.(is_null($value) ? 'NULL' : $this->quote($value));
        }
        // 去掉逗号.
        $columns = substr($columns, 0, strlen($columns) - 1);
        $values = substr($values, 0, strlen($values) - 1);

        $sql_ignore = '';
        if ($on_dup === self::INSERT_ON_DUP_IGNORE) {
            $sql_ignore = 'IGNORE';
        }
        $sql_on_dup = '';
        if ($on_dup == self::INSERT_ON_DUP_UPDATE) {
            $sql_on_dup = 'ON DUPLICATE KEY UPDATE '.implode(',', $updates);
        }
        $real_sql = "INSERT {$sql_ignore} INTO {$table} ({$columns}) VALUES ({$values}) $sql_on_dup";
        $pstmt = $this->pdo->prepare($real_sql);
        $this->bindValue($pstmt, $params);
        $this->last_sql = $pstmt->queryString;

        return $pstmt->execute() ? $this->pdo->lastInsertId() : false;
    }

    /**
     * 加引号.
     *
     * @param $str
     *
     * @return string
     */
    protected function quote($str)
    {
        return $this->pdo->quote($str);
    }
    /**
     * 删除语句.
     *
     * @param string $table     表名.
     * @param null   $condition 删除条件,空删除所有.
     *
     * @return bool|int false表示失败.
     */
    public function delete($table, $condition = null)
    {
        $real_sql = 'DELETE FROM '.$table.' WHERE '.$this->buildWhere($condition);
        $pstmt = $this->pdo->prepare($real_sql);
        $this->bindValue($pstmt, $condition);
        $this->last_sql = $pstmt->queryString;

        return $pstmt->execute() ? $pstmt->rowCount() : false;
    }

    /**
     * 创建查询字段.
     *
     * @param mixed $fields 查询的字段.
     *
     * @return string
     */
    protected function getFields($fields)
    {
        if (empty($fields)) {
            $fields = '*';
        } elseif (is_array($fields)) {
            $fields = implode(',', $fields);
        }

        return $fields;
    }

    /**
     * 创建一个PrepareSQL.
     *
     * @param array  $params  数据.
     * @param string $add_str 连接字符串.
     * @param string $prefix  前缀.
     *
     * @return string
     */
    private function buildPreparedSql($params, $add_str, $prefix = '')
    {
        $sql = '';
        $str_added = false;
        foreach ($params as $key => $val) {
            if ($str_added) {
                $sql .= " {$add_str} ";
            } else {
                $str_added = true;
            }
            $sql .= "{$key} = :{$prefix}{$key}";
        }

        return $sql;
    }

    /**
     * 创建用于更新条件的数据(Where 语句).
     *
     * @param array  $condition 更新条件.
     * @param string $logical   连接逻辑,默认AND
     * @param string $prefix    前缀.
     *
     * @return string
     */
    protected function buildWhere($condition, $logical = 'AND', $prefix = '')
    {
        if (empty($condition)) {
            return '1=1';
        }

        return $this->buildPreparedSql($condition, $logical, $prefix);
    }

    /**
     * 创建用于更新字段的数据(update Set语句).
     *
     * @param array  $params 参数.
     * @param string $prefix 前缀.
     *
     * @return string
     */
    protected function buildSetParams($params, $prefix = '')
    {
        return $this->buildPreparedSql($params, ',', $prefix);
    }

    /**
     * 获取最后一次执行SQL.
     *
     * @return string
     */
    public function getLastSql()
    {
        return $this->last_sql;
    }

    /**
     * @param $sql
     * @param array $params
     * @param int   $type
     *
     * @return array|bool|int
     */
    public function query($sql, $params = array(), $type = \PDO::FETCH_ASSOC)
    {
        return $this->exec($sql, $params, true, $type);
    }

    /**
     * @param $sql
     * @param array $params
     *
     * @return array|bool|int
     */
    public function execute($sql, $params = array())
    {
        return $this->exec($sql, $params, false);
    }

    /**
     * @param $sql
     * @param array $params
     * @param bool  $query
     * @param int   $type
     *
     * @return array|bool|int
     */
    private function exec($sql, $params = array(), $query = false, $type = \PDO::FETCH_ASSOC)
    {
        $pstmt = $this->pdo->prepare($sql);
        foreach ((array) $params as $key => $val) {
            $pstmt->bindValue($key, $val);
        }
        $result = $pstmt->execute();
        if ($query) {
            return $pstmt->fetchAll($type);
        }

        return $result == true ? $pstmt->rowCount() : false;
    }
}
