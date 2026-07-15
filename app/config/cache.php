<?php
/**
 * 缓存配置文件
 */
return [
    // 缓存类型 file/redis
    'type' => 'file',
    
    // 文件缓存配置
    'file' => [
        'path' => RUNTIME_PATH . 'cache/data/',
        'prefix' => 'gov_',
        'expire' => 3600
    ],
    
    // Redis配置
    'redis' => [
        'host' => '127.0.0.1',
        'port' => 6379,
        'password' => '',
        'database' => 0,
        'prefix' => 'gov:',
        'expire' => 3600
    ]
];
