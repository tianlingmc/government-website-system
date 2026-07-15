# 政府官网系统 v1.0

专业级政府官网解决方案，基于 PHP8.1 + MySQL8.0 开发，支持前后台全功能，采用类似 WordPress 的部署方式。

## 功能特性

### 前台功能
[-] 🏠 响应式首页设计
[-] 📢 政务公告展示
[-] 📄 政策法规查询
[-] ⚖️ 裁判文书检索
[-] 💬 在线咨询投诉
[-] 👤 用户登录授权
[-] 🖼️ 图片懒加载
[-] 📱 移动端适配

### 后台功能
[-] 📊 可视化数据仪表盘
[-] 📝 内容管理系统
[-] ⚖️ 裁判文书管理
[-] 👥 用户权限管理
[-] 🖼️ 媒体库管理
[-] 🌐 文字配置管理
[-] ⚙️ 网站设置
[-] 📧 SMTP邮箱配置
[-] 💾 数据备份
[-] 📋 操作日志

### 技术特点
[-] PHP8.1 + MySQL8.0
[-] MVC架构设计
[-] 前端文字全后台自定义
[-] 文件哈希重命名存储
[-] Redis缓存支持
[-] 图片懒加载动画
[-] RBAC权限管理
[-] 响应式布局

## 环境要求

> PHP >= 8.1
> MySQL >= 8.0
> Redis (推荐)
> Nginx/Apache

### PHP扩展要求
> pdo_mysql
> gd
> redis (推荐)
> fileinfo
> mbstring
> openssl

## 安装部署

### 方式一：宝塔面板部署（推荐）

1. **创建网站**
   - 登录宝塔面板
   - 创建新网站，绑定域名
   - 选择 PHP 版本 8.1

2. **上传代码**
   - 将 `v1.0` 文件夹内所有文件上传到网站根目录

3. **设置运行目录**
   - 将网站运行目录设置为 `/public`

4. **配置伪静态**
   
   **Nginx:**
   ```nginx
   location / {
       if (!-e $request_filename) {
           rewrite ^(.*)$ /index.php?s=$1 last;
       }
   }
   
   location ~ \.php$ {
       fastcgi_pass 127.0.0.1:9000;
       fastcgi_index index.php;
       include fastcgi_params;
   }
   ```
   
   **Apache:**
   ```apache
   <IfModule mod_rewrite.c>
       RewriteEngine On
       RewriteCond %{REQUEST_FILENAME} !-f
       RewriteCond %{REQUEST_FILENAME} !-d
       RewriteRule ^(.*)$ index.php?s=$1 [QSA,PT,L]
   </IfModule>
   ```

5. **设置目录权限**
   ```bash
   chmod -R 755 runtime/
   chmod -R 755 app/config/
   chmod -R 755 public/media/
   ```

6. **运行安装向导**
   - 访问 `http://您的域名/install/`
   - 按步骤完成数据库配置、站点设置、管理员创建

### 方式二：手动部署

1. 上传代码到服务器
2. 配置Web服务器指向 `public` 目录
3. 配置伪静态规则
4. 设置目录权限
5. 访问安装向导完成安装

## 目录结构

```
v1.0/
├── public/              # 网站根目录
│   ├── index.php        # 前台入口
│   ├── admin.php        # 后台入口
│   ├── install/         # 安装向导
│   ├── assets/          # 前台静态资源
│   ├── admin/           # 后台静态资源
│   └── media/           # 上传文件存储
├── app/                 # 应用目录
│   ├── config/          # 配置文件
│   ├── controller/      # 控制器
│   ├── model/           # 模型
│   └── view/            # 视图模板
├── core/                # 核心框架
├── runtime/             # 运行时目录
├── database/            # 数据库文件
└── vendor/              # Composer依赖
```

## 使用说明

### 后台管理
- 访问 `http://您的域名/admin.php`
- 使用安装时创建的管理员账号登录

### 文字自定义
所有前端显示文字均可在后台「文字配置」模块自定义，无需修改代码。

### 媒体上传
支持图片、视频、文档等多种格式，文件自动哈希重命名存储。

### 裁判文书
支持全文检索、分类筛选、附件下载等功能。

## 安全建议

1. 安装完成后删除 `public/install` 目录
2. 定期修改管理员密码
3. 启用HTTPS访问
4. 配置Web应用防火墙(WAF)
5. 定期备份数据库

## 更新日志

### v1.0 (2026-04-04)
- 初始版本发布
- 完整前后台功能
- 安装向导系统
- 裁判文书检索
- 媒体库管理

## 技术支持

如有问题，请联系技术支持。

## 许可证

MIT License
