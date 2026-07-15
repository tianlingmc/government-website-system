<nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= $currentMenu ?? '' === 'dashboard' ? 'active' : '' ?>" href="/admin">
                    <i class="bi bi-house-door"></i> 控制台
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentMenu ?? '' === 'notice' ? 'active' : '' ?>" href="/admin/notice">
                    <i class="bi bi-megaphone"></i> 公告管理
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentMenu ?? '' === 'policy' ? 'active' : '' ?>" href="/admin/policy">
                    <i class="bi bi-file-text"></i> 政策法规
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentMenu ?? '' === 'judicial' ? 'active' : '' ?>" href="/admin/judicial">
                    <i class="bi bi-bank"></i> 裁判文书
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentMenu ?? '' === 'consult' ? 'active' : '' ?>" href="/admin/consult">
                    <i class="bi bi-chat-dots"></i> 咨询管理
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentMenu ?? '' === 'user' ? 'active' : '' ?>" href="/admin/user">
                    <i class="bi bi-people"></i> 用户管理
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentMenu ?? '' === 'media' ? 'active' : '' ?>" href="/admin/media">
                    <i class="bi bi-images"></i> 媒体库
                </a>
            </li>
        </ul>
        
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>系统设置</span>
        </h6>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= $currentMenu ?? '' === 'settings' ? 'active' : '' ?>" href="/admin/settings">
                    <i class="bi bi-gear"></i> 网站设置
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentMenu ?? '' === 'language' ? 'active' : '' ?>" href="/admin/language">
                    <i class="bi bi-translate"></i> 文字配置
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentMenu ?? '' === 'nav' ? 'active' : '' ?>" href="/admin/nav">
                    <i class="bi bi-menu-button"></i> 导航管理
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentMenu ?? '' === 'admin' ? 'active' : '' ?>" href="/admin/admin">
                    <i class="bi bi-person-badge"></i> 管理员
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentMenu ?? '' === 'role' ? 'active' : '' ?>" href="/admin/role">
                    <i class="bi bi-shield-check"></i> 角色权限
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentMenu ?? '' === 'log' ? 'active' : '' ?>" href="/admin/log">
                    <i class="bi bi-journal-text"></i> 操作日志
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentMenu ?? '' === 'backup' ? 'active' : '' ?>" href="/admin/backup">
                    <i class="bi bi-cloud-download"></i> 数据备份
                </a>
            </li>
        </ul>
    </div>
</nav>

<style>
.sidebar {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    z-index: 100;
    padding: 48px 0 0;
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
}
.sidebar-sticky {
    position: relative;
    top: 0;
    height: calc(100vh - 48px);
    padding-top: .5rem;
    overflow-x: hidden;
    overflow-y: auto;
}
.sidebar .nav-link {
    font-weight: 500;
    color: #333;
}
.sidebar .nav-link.active {
    color: #2470dc;
}
.sidebar-heading {
    font-size: .75rem;
    text-transform: uppercase;
}
</style>