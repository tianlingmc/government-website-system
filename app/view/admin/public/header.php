<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? '管理后台'); ?> - 政府官网系统</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- AdminLTE 3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" rel="stylesheet">
    <!-- 自定义样式 -->
    <link href="/admin/assets/css/admin.css" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- 导航栏 -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="/" class="nav-link" target="_blank"><i class="fas fa-home me-1"></i>访问前台</a>
                </li>
            </ul>
            
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <?php if (!empty($notificationCount)): ?>
                        <span class="badge badge-warning navbar-badge"><?php echo $notificationCount; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">
                        <i class="far fa-user-circle me-1"></i><?php echo htmlspecialchars($admin['name'] ?? '管理员'); ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="/admin/profile" class="dropdown-item"><i class="fas fa-user me-2"></i>个人资料</a>
                        <a href="/admin/password" class="dropdown-item"><i class="fas fa-key me-2"></i>修改密码</a>
                        <div class="dropdown-divider"></div>
                        <a href="/admin/logout" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>退出登录</a>
                    </div>
                </li>
            </ul>
        </nav>
        
        <!-- 侧边栏 -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="/admin" class="brand-link">
                <span class="brand-text font-weight-light">政府官网管理后台</span>
            </a>
            
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">
                        <li class="nav-item">
                            <a href="/admin" class="nav-link <?php echo $currentMenu == 'dashboard' ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>控制台</p>
                            </a>
                        </li>
                        
                        <li class="nav-header">内容管理</li>
                        <li class="nav-item">
                            <a href="/admin/notice" class="nav-link <?php echo $currentMenu == 'notice' ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-bullhorn"></i>
                                <p>公告管理</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/policy" class="nav-link <?php echo $currentMenu == 'policy' ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>政策法规</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/judicial" class="nav-link <?php echo $currentMenu == 'judicial' ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-gavel"></i>
                                <p>裁判文书</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/consult" class="nav-link <?php echo $currentMenu == 'consult' ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-comments"></i>
                                <p>咨询投诉<?php if (!empty($pendingConsultCount)): ?><span class="badge badge-danger right"><?php echo $pendingConsultCount; ?></span><?php endif; ?></p>
                            </a>
                        </li>
                        
                        <li class="nav-header">系统管理</li>
                        <li class="nav-item">
                            <a href="/admin/media" class="nav-link <?php echo $currentMenu == 'media' ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-images"></i>
                                <p>媒体库</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/language" class="nav-link <?php echo $currentMenu == 'language' ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-language"></i>
                                <p>文字配置</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/nav" class="nav-link <?php echo $currentMenu == 'nav' ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-bars"></i>
                                <p>导航管理</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/settings" class="nav-link <?php echo $currentMenu == 'settings' ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>网站设置</p>
                            </a>
                        </li>
                        
                        <li class="nav-header">用户权限</li>
                        <li class="nav-item">
                            <a href="/admin/admin" class="nav-link <?php echo $currentMenu == 'admin' ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-user-shield"></i>
                                <p>管理员</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/user" class="nav-link <?php echo $currentMenu == 'user' ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-users"></i>
                                <p>前台用户</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/role" class="nav-link <?php echo $currentMenu == 'role' ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-user-tag"></i>
                                <p>角色权限</p>
                            </a>
                        </li>
                        
                        <li class="nav-header">运维管理</li>
                        <li class="nav-item">
                            <a href="/admin/log" class="nav-link <?php echo $currentMenu == 'log' ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-history"></i>
                                <p>操作日志</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/backup" class="nav-link <?php echo $currentMenu == 'backup' ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-database"></i>
                                <p>数据备份</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
        
        <!-- 内容区域 -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"><?php echo htmlspecialchars($pageTitle ?? '控制台'); ?></h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin">首页</a></li>
                                <?php if (!empty($breadcrumb)): ?>
                                    <?php foreach ($breadcrumb as $item): ?>
                                    <li class="breadcrumb-item <?php echo empty($item['url']) ? 'active' : ''; ?>">
                                        <?php if (!empty($item['url'])): ?>
                                        <a href="<?php echo $item['url']; ?>"><?php echo htmlspecialchars($item['name']); ?></a>
                                        <?php else: ?>
                                        <?php echo htmlspecialchars($item['name']); ?>
                                        <?php endif; ?>
                                    </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            
            <section class="content">
                <div class="container-fluid">
