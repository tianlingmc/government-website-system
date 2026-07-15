<?php
/**
 * 政府官网系统 - 前台入口
 * 类似WordPress的部署方式
 */

// 定义根目录
define('ROOT_PATH', dirname(__DIR__) . '/');
define('APP_PATH', ROOT_PATH . 'app/');
define('CORE_PATH', ROOT_PATH . 'core/');
define('RUNTIME_PATH', ROOT_PATH . 'runtime/');
define('PUBLIC_PATH', __DIR__ . '/');

// 检查是否已安装
$configFile = APP_PATH . 'config/database.php';
if (!file_exists($configFile)) {
    // 未安装，跳转到安装向导
    header('Location: /install/');
    exit;
}

// 加载核心框架
require_once CORE_PATH . 'App.php';

// 启动应用
\core\App::run();
