-- 政府官网系统数据库结构
-- 版本: v1.0
-- 字符集: utf8mb4

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- 管理员表
-- ----------------------------
DROP TABLE IF EXISTS `gov_admin`;
CREATE TABLE `gov_admin` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `username` varchar(50) NOT NULL COMMENT '登录账号',
    `password` varchar(255) NOT NULL COMMENT '密码(bcrypt加密)',
    `name` varchar(50) NOT NULL COMMENT '真实姓名',
    `phone` varchar(20) DEFAULT NULL COMMENT '手机号',
    `email` varchar(100) DEFAULT NULL COMMENT '邮箱',
    `role_id` int(11) unsigned DEFAULT '0' COMMENT '角色ID',
    `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
    `is_super` tinyint(1) unsigned DEFAULT '0' COMMENT '是否超级管理员 0否 1是',
    `is_admin` tinyint(1) unsigned DEFAULT '1' COMMENT '是否管理员 0前台用户 1管理员',
    `auth_status` tinyint(1) unsigned DEFAULT '0' COMMENT '授权状态 0待授权 1已授权 2已拒绝',
    `auth_time` datetime DEFAULT NULL COMMENT '授权时间',
    `auth_expire_time` datetime DEFAULT NULL COMMENT '授权过期时间',
    `last_login_time` datetime DEFAULT NULL COMMENT '最后登录时间',
    `last_login_ip` varchar(50) DEFAULT NULL COMMENT '最后登录IP',
    `login_fail_count` int(11) unsigned DEFAULT '0' COMMENT '登录失败次数',
    `lock_time` datetime DEFAULT NULL COMMENT '锁定时间',
    `status` tinyint(1) unsigned DEFAULT '1' COMMENT '状态 0禁用 1启用',
    `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
    `update_time` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` (`username`),
    KEY `role_id` (`role_id`),
    KEY `status` (`status`),
    KEY `auth_status` (`auth_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='管理员表';

-- ----------------------------
-- 前台用户表
-- ----------------------------
DROP TABLE IF EXISTS `gov_user`;
CREATE TABLE `gov_user` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `username` varchar(50) NOT NULL COMMENT '登录账号',
    `password` varchar(255) NOT NULL COMMENT '密码(bcrypt加密)',
    `real_name` varchar(50) DEFAULT NULL COMMENT '真实姓名',
    `phone` varchar(20) DEFAULT NULL COMMENT '手机号',
    `email` varchar(100) DEFAULT NULL COMMENT '邮箱',
    `id_card` varchar(50) DEFAULT NULL COMMENT '身份证号(脱敏存储)',
    `user_intro` text COMMENT '个人简介',
    `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
    `is_admin` tinyint(1) unsigned DEFAULT '0' COMMENT '是否管理员 0否 1是',
    `auth_status` tinyint(1) unsigned DEFAULT '0' COMMENT '授权状态 0待授权 1已授权 2已拒绝',
    `auth_time` datetime DEFAULT NULL COMMENT '授权时间',
    `auth_expire_time` datetime DEFAULT NULL COMMENT '授权过期时间',
    `last_login_time` datetime DEFAULT NULL COMMENT '最后登录时间',
    `last_login_ip` varchar(50) DEFAULT NULL COMMENT '最后登录IP',
    `login_fail_count` int(11) unsigned DEFAULT '0' COMMENT '登录失败次数',
    `lock_time` datetime DEFAULT NULL COMMENT '锁定时间',
    `status` tinyint(1) unsigned DEFAULT '1' COMMENT '状态 0禁用 1启用',
    `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
    `update_time` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` (`username`),
    KEY `status` (`status`),
    KEY `auth_status` (`auth_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='前台用户表';

-- ----------------------------
-- 管理员角色表
-- ----------------------------
DROP TABLE IF EXISTS `gov_admin_role`;
CREATE TABLE `gov_admin_role` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `role_name` varchar(50) NOT NULL COMMENT '角色名称',
    `role_desc` varchar(255) DEFAULT NULL COMMENT '角色描述',
    `permissions` text COMMENT '权限列表(JSON格式)',
    `sort` int(11) unsigned DEFAULT '0' COMMENT '排序',
    `status` tinyint(1) unsigned DEFAULT '1' COMMENT '状态 0禁用 1启用',
    `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
    `update_time` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='管理员角色表';

-- 插入默认角色
INSERT INTO `gov_admin_role` (`id`, `role_name`, `role_desc`, `permissions`, `status`) VALUES
(1, '超级管理员', '拥有所有权限', '["*"]', 1),
(2, '内容管理员', '管理网站内容', '["content.*","media.*"]', 1),
(3, '普通管理员', '基础管理权限', '["content.view","user.view"]', 1);

-- ----------------------------
-- 网站设置表
-- ----------------------------
DROP TABLE IF EXISTS `gov_settings`;
CREATE TABLE `gov_settings` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `setting_key` varchar(100) NOT NULL COMMENT '设置键',
    `setting_value` text COMMENT '设置值',
    `setting_group` varchar(50) DEFAULT 'general' COMMENT '设置分组',
    `setting_desc` varchar(255) DEFAULT NULL COMMENT '设置描述',
    `is_system` tinyint(1) unsigned DEFAULT '0' COMMENT '是否系统设置',
    `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
    `update_time` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `setting_key` (`setting_key`),
    KEY `setting_group` (`setting_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='网站设置表';

-- 插入默认设置
INSERT INTO `gov_settings` (`setting_key`, `setting_value`, `setting_group`, `setting_desc`) VALUES
('site_title', '政府官网系统', 'general', '网站标题'),
('site_subtitle', '政务服务门户网站', 'general', '网站副标题'),
('site_url', '', 'general', '网站URL'),
('site_logo', '', 'general', '网站Logo'),
('site_favicon', '', 'general', '网站图标'),
('icp_number', '', 'footer', 'ICP备案号'),
('police_number', '', 'footer', '公安备案号'),
('copyright', '© 2026 版权所有', 'footer', '版权信息'),
('footer_copyright_order', '1', 'footer', '版权板块顺序'),
('footer_record_order', '2', 'footer', '备案板块顺序'),
('seo_title', '', 'seo', 'SEO标题'),
('seo_keywords', '', 'seo', 'SEO关键词'),
('seo_description', '', 'seo', 'SEO描述'),
('lazyload_enabled', '1', 'feature', '是否启用懒加载'),
('lazyload_animation', 'blur', 'feature', '懒加载动画类型'),
('login_fail_limit', '5', 'security', '登录失败限制次数'),
('login_lock_time', '30', 'security', '登录锁定时间(分钟)'),
('smtp_host', '', 'smtp', 'SMTP服务器'),
('smtp_port', '465', 'smtp', 'SMTP端口'),
('smtp_username', '', 'smtp', 'SMTP用户名'),
('smtp_password', '', 'smtp', 'SMTP密码'),
('smtp_encryption', 'ssl', 'smtp', 'SMTP加密方式'),
('smtp_from', '', 'smtp', '发件人邮箱'),
('smtp_from_name', '', 'smtp', '发件人名称'),
('contact_phone', '12345 政务服务热线', 'contact', '联系电话'),
('contact_email', 'contact@gov.cn', 'contact', '联系邮箱'),
('contact_address', '某某市政府大楼', 'contact', '联系地址'),
('contact_work_time', '周一至周五 9:00-17:00', 'contact', '工作时间');

-- ----------------------------
-- 前端文字配置表
-- ----------------------------
DROP TABLE IF EXISTS `gov_language`;
CREATE TABLE `gov_language` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `lang_key` varchar(200) NOT NULL COMMENT '语言键',
    `lang_value` text COMMENT '语言值',
    `lang_group` varchar(50) DEFAULT 'common' COMMENT '分组',
    `module` varchar(50) DEFAULT 'common' COMMENT '所属模块',
    `is_default` tinyint(1) unsigned DEFAULT '0' COMMENT '是否默认值',
    `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
    `update_time` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `lang_key` (`lang_key`),
    KEY `lang_group` (`lang_group`),
    KEY `module` (`module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='前端文字配置表';

-- 插入默认语言配置
INSERT INTO `gov_language` (`lang_key`, `lang_value`, `lang_group`, `module`) VALUES
-- 通用
('common.home', '首页', 'common', 'common'),
('common.about', '关于我们', 'common', 'common'),
('common.news', '新闻动态', 'common', 'common'),
('common.service', '政务服务', 'common', 'common'),
('common.contact', '联系我们', 'common', 'common'),
('common.search', '搜索', 'common', 'common'),
('common.login', '登录', 'common', 'common'),
('common.logout', '退出', 'common', 'common'),
('common.register', '注册', 'common', 'common'),
('common.submit', '提交', 'common', 'common'),
('common.reset', '重置', 'common', 'common'),
('common.back', '返回', 'common', 'common'),
('common.more', '查看更多', 'common', 'common'),
('common.loading', '加载中...', 'common', 'common'),
('common.no_data', '暂无数据', 'common', 'common'),
('common.error', '出错了', 'common', 'common'),
('common.success', '操作成功', 'common', 'common'),

-- 导航
('nav.gov_public', '政务公开', 'nav', 'common'),
('nav.public_service', '公众服务', 'nav', 'common'),
('nav.interaction', '互动交流', 'nav', 'common'),
('nav.judicial', '裁判文书', 'nav', 'common'),

-- 用户相关
('user.username', '用户名', 'user', 'common'),
('user.password', '密码', 'user', 'common'),
('user.email', '邮箱', 'user', 'common'),
('user.phone', '手机号', 'user', 'common'),
('user.real_name', '真实姓名', 'user', 'common'),
('user.forgot_password', '忘记密码', 'user', 'common'),
('user.auth_pending', '账号待授权', 'user', 'common'),
('user.auth_approved', '账号已授权', 'user', 'common'),
('user.auth_rejected', '账号已拒绝', 'user', 'common'),

-- 裁判文书
('judicial.search_placeholder', '请输入案件名称、案号或关键词', 'judicial', 'judicial'),
('judicial.search_btn', '检索', 'judicial', 'judicial'),
('judicial.case_no', '案号', 'judicial', 'judicial'),
('judicial.case_name', '案件名称', 'judicial', 'judicial'),
('judicial.court', '审理法院', 'judicial', 'judicial'),
('judicial.judge_date', '裁判日期', 'judicial', 'judicial'),
('judicial.case_type', '案件类型', 'judicial', 'judicial'),
('judicial.no_result', '未找到相关文书', 'judicial', 'judicial');

-- ----------------------------
-- 导航菜单表
-- ----------------------------
DROP TABLE IF EXISTS `gov_nav`;
CREATE TABLE `gov_nav` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `parent_id` int(11) unsigned DEFAULT '0' COMMENT '父级ID',
    `nav_name` varchar(100) NOT NULL COMMENT '导航名称',
    `nav_url` varchar(255) DEFAULT NULL COMMENT '链接地址',
    `nav_type` tinyint(1) unsigned DEFAULT '1' COMMENT '类型 1内部链接 2外部链接',
    `target` varchar(20) DEFAULT '_self' COMMENT '打开方式',
    `icon` varchar(100) DEFAULT NULL COMMENT '图标',
    `sort` int(11) unsigned DEFAULT '0' COMMENT '排序',
    `is_show` tinyint(1) unsigned DEFAULT '1' COMMENT '是否显示',
    `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
    `update_time` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `parent_id` (`parent_id`),
    KEY `sort` (`sort`),
    KEY `is_show` (`is_show`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='导航菜单表';

-- ----------------------------
-- 媒体文件表
-- ----------------------------
DROP TABLE IF EXISTS `gov_media`;
CREATE TABLE `gov_media` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(11) unsigned DEFAULT '0' COMMENT '上传用户ID',
    `user_type` tinyint(1) unsigned DEFAULT '1' COMMENT '用户类型 1管理员 2前台用户',
    `file_name` varchar(255) NOT NULL COMMENT '原始文件名',
    `file_hash` varchar(64) NOT NULL COMMENT '文件哈希名',
    `file_path` varchar(500) NOT NULL COMMENT '文件路径',
    `file_url` varchar(500) NOT NULL COMMENT '文件URL',
    `file_type` varchar(50) NOT NULL COMMENT '文件类型',
    `file_mime` varchar(100) DEFAULT NULL COMMENT 'MIME类型',
    `file_size` int(11) unsigned DEFAULT '0' COMMENT '文件大小(字节)',
    `file_ext` varchar(20) DEFAULT NULL COMMENT '文件扩展名',
    `image_width` int(11) unsigned DEFAULT '0' COMMENT '图片宽度',
    `image_height` int(11) unsigned DEFAULT '0' COMMENT '图片高度',
    `is_used` tinyint(1) unsigned DEFAULT '0' COMMENT '是否被使用',
    `used_count` int(11) unsigned DEFAULT '0' COMMENT '使用次数',
    `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `file_hash` (`file_hash`),
    KEY `file_type` (`file_type`),
    KEY `is_used` (`is_used`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='媒体文件表';

-- ----------------------------
-- 公告表
-- ----------------------------
DROP TABLE IF EXISTS `gov_notice`;
CREATE TABLE `gov_notice` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL COMMENT '标题',
    `content` longtext COMMENT '内容',
    `summary` text COMMENT '摘要',
    `cover` varchar(255) DEFAULT NULL COMMENT '封面图',
    `author` varchar(50) DEFAULT NULL COMMENT '作者',
    `source` varchar(100) DEFAULT NULL COMMENT '来源',
    `views` int(11) unsigned DEFAULT '0' COMMENT '浏览量',
    `sort` int(11) unsigned DEFAULT '0' COMMENT '排序',
    `is_top` tinyint(1) unsigned DEFAULT '0' COMMENT '是否置顶',
    `is_important` tinyint(1) unsigned DEFAULT '0' COMMENT '是否重要',
    `status` tinyint(1) unsigned DEFAULT '1' COMMENT '状态 0草稿 1已发布 2已下架',
    `publish_time` datetime DEFAULT NULL COMMENT '发布时间',
    `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
    `update_time` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `status` (`status`),
    KEY `is_top` (`is_top`),
    KEY `publish_time` (`publish_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='公告表';

-- ----------------------------
-- 政策法规表
-- ----------------------------
DROP TABLE IF EXISTS `gov_policy`;
CREATE TABLE `gov_policy` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL COMMENT '标题',
    `content` longtext COMMENT '内容',
    `summary` text COMMENT '摘要',
    `doc_no` varchar(100) DEFAULT NULL COMMENT '文号',
    `publish_org` varchar(100) DEFAULT NULL COMMENT '发布机构',
    `publish_date` date DEFAULT NULL COMMENT '发布日期',
    `effective_date` date DEFAULT NULL COMMENT '生效日期',
    `category_id` int(11) unsigned DEFAULT '0' COMMENT '分类ID',
    `attachment` varchar(255) DEFAULT NULL COMMENT '附件',
    `views` int(11) unsigned DEFAULT '0' COMMENT '浏览量',
    `status` tinyint(1) unsigned DEFAULT '1' COMMENT '状态',
    `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
    `update_time` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `category_id` (`category_id`),
    KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='政策法规表';

-- ----------------------------
-- 裁判文书表
-- ----------------------------
DROP TABLE IF EXISTS `gov_judicial`;
CREATE TABLE `gov_judicial` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `case_no` varchar(100) NOT NULL COMMENT '案号',
    `case_name` varchar(255) NOT NULL COMMENT '案件名称',
    `court` varchar(100) NOT NULL COMMENT '审理法院',
    `case_type` varchar(50) DEFAULT NULL COMMENT '案件类型',
    `case_cause` varchar(200) DEFAULT NULL COMMENT '案由',
    `judge_date` date DEFAULT NULL COMMENT '裁判日期',
    `publish_date` date DEFAULT NULL COMMENT '发布日期',
    `content` longtext COMMENT '文书内容',
    `content_desensitized` longtext COMMENT '脱敏后内容',
    `parties` text COMMENT '当事人信息(JSON)',
    `judge` varchar(50) DEFAULT NULL COMMENT '审判员',
    `clerk` varchar(50) DEFAULT NULL COMMENT '书记员',
    `attachment` varchar(255) DEFAULT NULL COMMENT '附件',
    `views` int(11) unsigned DEFAULT '0' COMMENT '浏览量',
    `download_count` int(11) unsigned DEFAULT '0' COMMENT '下载次数',
    `check_status` tinyint(1) unsigned DEFAULT '0' COMMENT '审核状态 0待审核 1已通过 2已驳回',
    `check_remark` text COMMENT '审核备注',
    `is_public` tinyint(1) unsigned DEFAULT '0' COMMENT '是否公开',
    `status` tinyint(1) unsigned DEFAULT '1' COMMENT '状态',
    `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
    `update_time` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `case_no` (`case_no`),
    KEY `court` (`court`),
    KEY `case_type` (`case_type`),
    KEY `judge_date` (`judge_date`),
    KEY `check_status` (`check_status`),
    KEY `is_public` (`is_public`),
    FULLTEXT KEY `content` (`content`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='裁判文书表';

-- ----------------------------
-- 咨询投诉表
-- ----------------------------
DROP TABLE IF EXISTS `gov_consult`;
CREATE TABLE `gov_consult` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(11) unsigned DEFAULT '0' COMMENT '用户ID 0表示游客',
    `type` tinyint(1) unsigned DEFAULT '1' COMMENT '类型 1咨询 2投诉 3建议',
    `title` varchar(255) NOT NULL COMMENT '标题',
    `content` text COMMENT '内容',
    `contact_name` varchar(50) DEFAULT NULL COMMENT '联系人',
    `contact_phone` varchar(20) DEFAULT NULL COMMENT '联系电话',
    `contact_email` varchar(100) DEFAULT NULL COMMENT '联系邮箱',
    `attachment` varchar(255) DEFAULT NULL COMMENT '附件',
    `reply_content` text COMMENT '回复内容',
    `reply_time` datetime DEFAULT NULL COMMENT '回复时间',
    `reply_user_id` int(11) unsigned DEFAULT '0' COMMENT '回复人ID',
    `status` tinyint(1) unsigned DEFAULT '0' COMMENT '状态 0待处理 1处理中 2已回复 3已关闭',
    `is_public` tinyint(1) unsigned DEFAULT '0' COMMENT '是否公开',
    `ip_address` varchar(50) DEFAULT NULL COMMENT 'IP地址',
    `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
    `update_time` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `type` (`type`),
    KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='咨询投诉表';

-- ----------------------------
-- 操作日志表
-- ----------------------------
DROP TABLE IF EXISTS `gov_operation_log`;
CREATE TABLE `gov_operation_log` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(11) unsigned DEFAULT '0' COMMENT '用户ID',
    `user_type` tinyint(1) unsigned DEFAULT '1' COMMENT '用户类型 1管理员 2前台用户',
    `username` varchar(50) DEFAULT NULL COMMENT '用户名',
    `module` varchar(50) DEFAULT NULL COMMENT '操作模块',
    `action` varchar(50) DEFAULT NULL COMMENT '操作动作',
    `content` text COMMENT '操作内容',
    `ip_address` varchar(50) DEFAULT NULL COMMENT 'IP地址',
    `user_agent` varchar(500) DEFAULT NULL COMMENT 'User-Agent',
    `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `module` (`module`),
    KEY `create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='操作日志表';

-- ----------------------------
-- 登录日志表
-- ----------------------------
DROP TABLE IF EXISTS `gov_login_log`;
CREATE TABLE `gov_login_log` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(11) unsigned DEFAULT '0' COMMENT '用户ID',
    `user_type` tinyint(1) unsigned DEFAULT '1' COMMENT '用户类型',
    `username` varchar(50) DEFAULT NULL COMMENT '用户名',
    `login_type` tinyint(1) unsigned DEFAULT '1' COMMENT '登录类型 1账号密码 2邮箱 3手机',
    `login_status` tinyint(1) unsigned DEFAULT '1' COMMENT '登录状态 1成功 0失败',
    `fail_reason` varchar(255) DEFAULT NULL COMMENT '失败原因',
    `ip_address` varchar(50) DEFAULT NULL COMMENT 'IP地址',
    `user_agent` varchar(500) DEFAULT NULL COMMENT 'User-Agent',
    `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `login_status` (`login_status`),
    KEY `create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='登录日志表';

-- ----------------------------
-- IP黑名单表
-- ----------------------------
DROP TABLE IF EXISTS `gov_ip_blacklist`;
CREATE TABLE `gov_ip_blacklist` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `ip_address` varchar(50) NOT NULL COMMENT 'IP地址',
    `ip_type` tinyint(1) unsigned DEFAULT '1' COMMENT '类型 1单IP 2IP段',
    `reason` varchar(255) DEFAULT NULL COMMENT '封禁原因',
    `expire_time` datetime DEFAULT NULL COMMENT '过期时间',
    `status` tinyint(1) unsigned DEFAULT '1' COMMENT '状态',
    `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `ip_address` (`ip_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='IP黑名单表';

-- ----------------------------
-- 敏感词表
-- ----------------------------
DROP TABLE IF EXISTS `gov_sensitive_word`;
CREATE TABLE `gov_sensitive_word` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `word` varchar(100) NOT NULL COMMENT '敏感词',
    `replace_word` varchar(100) DEFAULT '*' COMMENT '替换词',
    `word_type` tinyint(1) unsigned DEFAULT '1' COMMENT '类型 1禁用词 2替换词',
    `status` tinyint(1) unsigned DEFAULT '1' COMMENT '状态',
    `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `word` (`word`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='敏感词表';

SET FOREIGN_KEY_CHECKS = 1;
