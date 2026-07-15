<?php
/**
 * 安全配置
 */
return [
    // 密码加密方式 bcrypt
    'password_algo' => PASSWORD_BCRYPT,
    
    // 密码复杂度要求
    'password_min_length' => 8,
    'password_require_upper' => false,
    'password_require_lower' => true,
    'password_require_number' => true,
    'password_require_special' => false,
    
    // 登录安全
    'login' => [
        'max_fail' => 5,           // 最大失败次数
        'lock_time' => 30,         // 锁定时间(分钟)
        'captcha_after_fail' => 3  // 几次失败后需要验证码
    ],
    
    // Session配置
    'session' => [
        'name' => 'gov_session',
        'expire' => 7200,          // session过期时间(秒)
        'secure' => true,          // 仅HTTPS传输
        'httponly' => true         // 禁止JS访问
    ],
    
    // 后台访问控制
    'admin' => [
        'ip_whitelist' => [],      // IP白名单
        'ip_blacklist' => [],      // IP黑名单
        'login_time_limit' => [    // 允许登录时间段
            'start' => '00:00',
            'end' => '23:59'
        ]
    ],
    
    // 上传安全
    'upload' => [
        'check_mime' => true,      // 检查MIME类型
        'check_extension' => true, // 检查扩展名
        'forbidden_ext' => ['php', 'php3', 'php4', 'php5', 'phtml', 'pht', 'jsp', 'jspx', 'asp', 'aspx', 'sh', 'bash', 'exe', 'dll'],
        'scan_virus' => false      // 是否扫描病毒(需要ClamAV)
    ],
    
    // CSRF防护
    'csrf' => [
        'token_name' => '_token',
        'expire' => 3600
    ]
];
