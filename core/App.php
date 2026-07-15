<?php
namespace core;

/**
 * 应用核心类
 */
class App {
    
    private static $instance = null;
    private $config = [];
    private $db = null;
    private $cache = null;
    
    /**
     * 获取单例实例
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * 启动应用
     */
    public static function run() {
        try {
            $app = self::getInstance();
            $app->init();
            $app->route();
        } catch (\Exception $e) {
            self::handleError($e);
        }
    }
    
    /**
     * 初始化
     */
    private function init() {
        // 注册自动加载
        $this->registerAutoload();
        
        // 加载配置
        $this->loadConfig();
        
        // 设置时区
        date_default_timezone_set($this->config['app']['timezone'] ?? 'Asia/Shanghai');
        
        // 启动session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // 连接数据库
        $this->connectDatabase();
        
        // 初始化缓存
        $this->initCache();
    }
    
    /**
     * 注册自动加载
     */
    private function registerAutoload() {
        spl_autoload_register(function ($class) {
            // 命名空间前缀
            $prefixes = [
                'core\\' => CORE_PATH,
                'app\\' => APP_PATH
            ];
            
            foreach ($prefixes as $prefix => $baseDir) {
                $len = strlen($prefix);
                if (strncmp($prefix, $class, $len) !== 0) {
                    continue;
                }
                
                $relativeClass = substr($class, $len);
                $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
                
                if (file_exists($file)) {
                    require $file;
                    return;
                }
            }
        });
    }
    
    /**
     * 加载配置
     */
    private function loadConfig() {
        $configPath = APP_PATH . 'config/';
        
        // 加载所有配置文件
        $configFiles = ['app.php', 'database.php', 'cache.php', 'security.php'];
        foreach ($configFiles as $file) {
            if (file_exists($configPath . $file)) {
                $this->config[pathinfo($file, PATHINFO_FILENAME)] = require $configPath . $file;
            }
        }
    }
    
    /**
     * 获取配置
     */
    public function getConfig($key = null, $default = null) {
        if ($key === null) {
            return $this->config;
        }
        
        $keys = explode('.', $key);
        $value = $this->config;
        
        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }
        
        return $value;
    }
    
    /**
     * 连接数据库
     */
    private function connectDatabase() {
        $dbConfig = $this->getConfig('database');
        
        // 检查是否已安装（如果没有配置或数据库名为空，跳转到安装）
        if (!$dbConfig || empty($dbConfig['database'])) {
            // 如果当前已经在安装页面，不跳转
            $currentUri = $_SERVER['REQUEST_URI'] ?? '/';
            if (strpos($currentUri, '/install/') === false) {
                header('Location: /install/');
                exit;
            }
            // 在安装页面时，不连接数据库
            return;
        }
        
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $dbConfig['host'],
            $dbConfig['port'],
            $dbConfig['database'],
            $dbConfig['charset']
        );
        
        try {
            $this->db = new \PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (\PDOException $e) {
            // 如果连接失败且未安装，跳转到安装页面
            if (!file_exists(ROOT_PATH . 'install.lock')) {
                header('Location: /install/');
                exit;
            }
            throw new \Exception('Database connection failed: ' . $e->getMessage());
        }
    }
    
    /**
     * 获取数据库连接
     */
    public function getDb() {
        return $this->db;
    }
    
    /**
     * 初始化缓存
     */
    private function initCache() {
        $cacheConfig = $this->getConfig('cache');
        if ($cacheConfig && $cacheConfig['type'] === 'redis') {
            try {
                $this->cache = new \Redis();
                $this->cache->connect($cacheConfig['host'], $cacheConfig['port']);
                if (!empty($cacheConfig['password'])) {
                    $this->cache->auth($cacheConfig['password']);
                }
                $this->cache->select($cacheConfig['database'] ?? 0);
            } catch (\Exception $e) {
                // Redis连接失败，使用文件缓存
                $this->cache = null;
            }
        }
    }
    
    /**
     * 获取缓存实例
     */
    public function getCache() {
        return $this->cache;
    }
    
    /**
     * 路由处理
     */
    private function route() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = trim($uri, '/');
        
        // 后台路由
        if (strpos($uri, 'admin') === 0 || $uri === 'admin.php') {
            $this->handleAdminRoute($uri);
            return;
        }
        
        // API路由
        if (strpos($uri, 'api/') === 0) {
            $this->handleApiRoute($uri);
            return;
        }
        
        // 前台路由
        $this->handleHomeRoute($uri);
    }
    
    /**
     * 处理前台路由
     */
    private function handleHomeRoute($uri) {
        // 默认首页
        if (empty($uri)) {
            $this->dispatch('home', 'Index', 'index');
            return;
        }
        
        // 解析路由
        $parts = explode('/', $uri);
        $controller = ucfirst($parts[0] ?? 'Index');
        $action = $parts[1] ?? 'index';
        $params = array_slice($parts, 2);
        
        $this->dispatch('home', $controller, $action, $params);
    }
    
    /**
     * 处理后台路由
     */
    private function handleAdminRoute($uri) {
        // 允许访问的登录相关页面
        $allowedWithoutLogin = ['admin/login', 'admin/login/index', 'admin/login/doLogin', 'admin/login/captcha', 'admin/login/logout'];
        
        // 检查登录状态
        if (!in_array($uri, $allowedWithoutLogin)) {
            if (!$this->checkAdminLogin()) {
                // 检查是否是普通用户登录（有user_id但没有admin权限）
                if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
                    // 普通用户尝试访问后台，显示403
                    $this->show403Error();
                }
                // 未登录，跳转到登录页
                header('Location: /admin/login');
                exit;
            }
        }
        
        $parts = explode('/', trim($uri, '/'));
        array_shift($parts); // 移除 'admin'
        
        $controller = ucfirst($parts[0] ?? 'Dashboard');
        $action = $parts[1] ?? 'index';
        $params = array_slice($parts, 2);
        
        $this->dispatch('admin', $controller, $action, $params);
    }
    
    /**
     * 处理API路由
     */
    private function handleApiRoute($uri) {
        header('Content-Type: application/json; charset=utf-8');
        
        $parts = explode('/', trim($uri, '/'));
        array_shift($parts); // 移除 'api'
        
        $version = $parts[0] ?? 'v1';
        $controller = ucfirst($parts[1] ?? 'Index');
        $action = $parts[2] ?? 'index';
        $params = array_slice($parts, 3);
        
        try {
            $this->dispatch('api.' . $version, $controller, $action, $params);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['code' => 500, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * 分发请求到控制器
     */
    private function dispatch($module, $controller, $action, $params = []) {
        $controllerClass = "\\app\\controller\\{$module}\\{$controller}";
        
        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller not found: {$controllerClass}");
        }
        
        $instance = new $controllerClass($this);
        
        if (!method_exists($instance, $action)) {
            throw new \Exception("Action not found: {$action}");
        }
        
        call_user_func_array([$instance, $action], $params);
    }
    
    /**
     * 检查后台登录状态
     */
    private function checkAdminLogin() {
        // 检查是否有admin_id且admin session存在
        if (!isset($_SESSION['admin_id']) || $_SESSION['admin_id'] <= 0) {
            return false;
        }
        
        // 检查是否是真正的管理员（有admin session且is_super或role_id存在）
        if (!isset($_SESSION['admin']) || !is_array($_SESSION['admin'])) {
            return false;
        }
        
        // 验证session中的admin_id与admin.id是否匹配
        if ($_SESSION['admin_id'] !== $_SESSION['admin']['id']) {
            return false;
        }
        
        return true;
    }
    
    /**
     * 显示403错误页面
     */
    private function show403Error() {
        http_response_code(403);
        $errorView = APP_PATH . 'view/home/error/403.php';
        if (file_exists($errorView)) {
            include $errorView;
        } else {
            echo '<h1>403 Forbidden</h1>';
            echo '<p>您没有权限访问此页面。</p>';
        }
        exit;
    }
    
    /**
     * 错误处理
     */
    private static function handleError($e) {
        $isDebug = true; // 生产环境设为false
        
        http_response_code(500);
        
        if ($isDebug) {
            echo '<h1>Error</h1>';
            echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '<pre>' . $e->getTraceAsString() . '</pre>';
        } else {
            echo '<h1>系统错误</h1>';
            echo '<p>请稍后重试或联系管理员</p>';
        }
    }
}
