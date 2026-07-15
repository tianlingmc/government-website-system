<?php
/**
 * 政府官网系统 - 后台入口
 */

// 定义根目录
define('ROOT_PATH', dirname(__DIR__) . '/');
define('APP_PATH', ROOT_PATH . 'app/');
define('CORE_PATH', ROOT_PATH . 'core/');
define('RUNTIME_PATH', ROOT_PATH . 'runtime/');
define('PUBLIC_PATH', __DIR__ . '/');

// 检查是否已安装
if (!file_exists(APP_PATH . 'config/database.php')) {
    header('Location: /install/');
    exit;
}

// 加载核心框架
require_once CORE_PATH . 'App.php';

// 启动应用（后台模式）
$_SERVER['REQUEST_URI'] = '/admin' . ($_SERVER['REQUEST_URI'] !== '/admin.php' ? substr($_SERVER['REQUEST_URI'], 10) : '');
\core\App::run();
