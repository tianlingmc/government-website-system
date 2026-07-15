<?php
/**
 * 应用配置文件
 */
return [
    // 应用名称
    'name' => '政府官网系统',
    
    // 应用版本
    'version' => '1.0.0',
    
    // 时区设置
    'timezone' => 'Asia/Shanghai',
    
    // 默认语言
    'lang' => 'zh-CN',
    
    // 调试模式
    'debug' => false,
    
    // URL模式 1普通模式 2PATHINFO模式
    'url_mode' => 2,
    
    // 默认控制器
    'default_controller' => 'Index',
    
    // 默认操作
    'default_action' => 'index',
    
    // 模板配置
    'view' => [
        'engine' => 'php',
        'path' => APP_PATH . 'view/',
        'cache' => RUNTIME_PATH . 'cache/view/',
        'suffix' => '.php'
    ],
    
    // 上传配置
    'upload' => [
        'max_size' => 10485760, // 10MB
        'allowed_ext' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'mp4'],
        'save_path' => PUBLIC_PATH . 'media/',
        'hash_name' => true
    ],
    
    // 分页配置
    'pagination' => [
        'page_size' => 15,
        'page_param' => 'page'
    ],
    
    // 安全配置
    'security' => [
        'csrf_token' => true,
        'xss_filter' => true,
        'sql_injection' => true
    ]
];
