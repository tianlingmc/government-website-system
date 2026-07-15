<?php
/**
 * 政府官网系统 - 安装向导
 * 类似WordPress的5分钟安装
 */

// 定义路径
define('ROOT_PATH', dirname(dirname(__DIR__)) . '/');
define('APP_PATH', ROOT_PATH . 'app/');
define('CORE_PATH', ROOT_PATH . 'core/');
define('INSTALL_PATH', __DIR__ . '/');

// 错误显示
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 启动session
session_start();

// 步骤处理
$step = isset($_GET['step']) ? intval($_GET['step']) : 1;
$step = max(1, min(5, $step));

// 检查是否已安装（通过install.lock文件判断）
if (file_exists(ROOT_PATH . 'install.lock') && $step < 5) {
    header('Location: /');
    exit;
}

// 安装类
class GovInstaller {
    
    // 步骤1: 环境检测
    public function showEnvironmentCheck() {
        $checks = [
            'PHP版本 >= 8.1' => [
                'status' => version_compare(PHP_VERSION, '8.1.0', '>='),
                'current' => PHP_VERSION,
                'required' => '8.1.0+'
            ],
            'MySQL扩展' => [
                'status' => extension_loaded('pdo_mysql'),
                'current' => extension_loaded('pdo_mysql') ? '已安装' : '未安装',
                'required' => '必需'
            ],
            'Redis扩展' => [
                'status' => extension_loaded('redis'),
                'current' => extension_loaded('redis') ? '已安装' : '未安装',
                'required' => '推荐'
            ],
            'GD库' => [
                'status' => extension_loaded('gd'),
                'current' => extension_loaded('gd') ? '已安装' : '未安装',
                'required' => '必需'
            ],
            '文件上传' => [
                'status' => ini_get('file_uploads'),
                'current' => ini_get('file_uploads') ? '已开启' : '已关闭',
                'required' => '必需'
            ],
            '目录可写: runtime' => [
                'status' => is_writable(ROOT_PATH . 'runtime'),
                'current' => is_writable(ROOT_PATH . 'runtime') ? '可写' : '不可写',
                'required' => '必需'
            ],
            '目录可写: app/config' => [
                'status' => is_writable(APP_PATH . 'config'),
                'current' => is_writable(APP_PATH . 'config') ? '可写' : '不可写',
                'required' => '必需'
            ],
            '目录可写: public/media' => [
                'status' => is_writable(ROOT_PATH . 'public/media'),
                'current' => is_writable(ROOT_PATH . 'public/media') ? '可写' : '不可写',
                'required' => '必需'
            ]
        ];
        
        $allPassed = true;
        foreach ($checks as $item) {
            if ($item['required'] === '必需' && !$item['status']) {
                $allPassed = false;
                break;
            }
        }
        ?>
        <h3>环境检测</h3>
        <p class="text-muted">请确保以下环境要求已满足</p>
        
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>检测项</th>
                    <th>当前状态</th>
                    <th>要求</th>
                    <th>结果</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($checks as $name => $check): ?>
                <tr>
                    <td><?php echo $name; ?></td>
                    <td><?php echo $check['current']; ?></td>
                    <td><?php echo $check['required']; ?></td>
                    <td>
                        <?php if ($check['status']): ?>
                            <span class="badge bg-success">✓</span>
                        <?php else: ?>
                            <span class="badge <?php echo $check['required'] === '必需' ? 'bg-danger' : 'bg-warning'; ?>">✗</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="d-flex justify-content-between mt-4">
            <a href="?step=1" class="btn btn-secondary disabled">上一步</a>
            <?php if ($allPassed): ?>
                <a href="?step=2" class="btn btn-primary">下一步：配置数据库</a>
            <?php else: ?>
                <button class="btn btn-danger" disabled>请修复上述问题后继续</button>
            <?php endif; ?>
        </div>
        <?php
    }
    
    // 步骤2: 数据库配置
    public function showDatabaseConfig() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleDatabaseConfig();
            return;
        }
        ?>
        <h3>数据库配置</h3>
        <p class="text-muted">请填写MySQL数据库连接信息</p>
        
        <form method="POST" action="?step=2">
            <div class="mb-3">
                <label class="form-label">数据库主机</label>
                <input type="text" name="db_host" class="form-control" value="localhost" required>
                <div class="form-text">通常为 localhost 或数据库服务器IP</div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">数据库端口</label>
                <input type="number" name="db_port" class="form-control" value="3306" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">数据库名</label>
                <input type="text" name="db_name" class="form-control" placeholder="gov_website" required>
                <div class="form-text">如果数据库不存在，系统将尝试自动创建</div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">数据库用户名</label>
                <input type="text" name="db_user" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">数据库密码</label>
                <input type="password" name="db_pass" class="form-control">
            </div>
            
            <div class="mb-3">
                <label class="form-label">数据表前缀</label>
                <input type="text" name="db_prefix" class="form-control" value="gov_">
                <div class="form-text">建议使用前缀避免表名冲突</div>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <a href="?step=1" class="btn btn-secondary">上一步</a>
                <button type="submit" class="btn btn-primary">下一步：站点设置</button>
            </div>
        </form>
        <?php
    }
    
    private function handleDatabaseConfig() {
        $config = [
            'host' => $_POST['db_host'] ?? 'localhost',
            'port' => $_POST['db_port'] ?? 3306,
            'database' => $_POST['db_name'] ?? '',
            'username' => $_POST['db_user'] ?? '',
            'password' => $_POST['db_pass'] ?? '',
            'prefix' => $_POST['db_prefix'] ?? 'gov_',
            'charset' => 'utf8mb4'
        ];
        
        // 测试连接
        try {
            $dsn = "mysql:host={$config['host']};port={$config['port']};charset={$config['charset']}";
            $pdo = new PDO($dsn, $config['username'], $config['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // 尝试创建数据库
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$config['database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
            // 保存配置到session
            $_SESSION['db_config'] = $config;
            
            header('Location: ?step=3');
            exit;
        } catch (PDOException $e) {
            $_SESSION['error'] = '数据库连接失败: ' . $e->getMessage();
            header('Location: ?step=2');
            exit;
        }
    }
    
    // 步骤3: 站点设置
    public function showSiteConfig() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleSiteConfig();
            return;
        }
        ?>
        <h3>站点设置</h3>
        <p class="text-muted">配置网站基本信息</p>
        
        <form method="POST" action="?step=3">
            <div class="mb-3">
                <label class="form-label">网站标题</label>
                <input type="text" name="site_title" class="form-control" placeholder="XX市人民政府" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">网站副标题</label>
                <input type="text" name="site_subtitle" class="form-control" placeholder="政务服务门户网站">
            </div>
            
            <div class="mb-3">
                <label class="form-label">网站域名</label>
                <input type="url" name="site_url" class="form-control" placeholder="https://www.example.gov.cn" required>
                <div class="form-text">请填写完整URL，包含https://</div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">ICP备案号</label>
                <input type="text" name="icp_number" class="form-control" placeholder="京ICP备XXXXXXXX号">
            </div>
            
            <div class="mb-3">
                <label class="form-label">公安备案号</label>
                <input type="text" name="police_number" class="form-control" placeholder="京公网安备XXXXXXXX号">
            </div>
            
            <div class="mb-3">
                <label class="form-label">版权信息</label>
                <input type="text" name="copyright" class="form-control" value="© 2026 版权所有">
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <a href="?step=2" class="btn btn-secondary">上一步</a>
                <button type="submit" class="btn btn-primary">下一步：创建管理员</button>
            </div>
        </form>
        <?php
    }
    
    private function handleSiteConfig() {
        $_SESSION['site_config'] = [
            'title' => $_POST['site_title'] ?? '',
            'subtitle' => $_POST['site_subtitle'] ?? '',
            'url' => $_POST['site_url'] ?? '',
            'icp_number' => $_POST['icp_number'] ?? '',
            'police_number' => $_POST['police_number'] ?? '',
            'copyright' => $_POST['copyright'] ?? ''
        ];
        
        header('Location: ?step=4');
        exit;
    }
    
    // 步骤4: 创建管理员
    public function showAdminConfig() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleInstall();
            return;
        }
        ?>
        <h3>创建超级管理员</h3>
        <p class="text-muted">设置后台管理账号</p>
        
        <form method="POST" action="?step=4" id="adminForm">
            <div class="mb-3">
                <label class="form-label">管理员账号</label>
                <input type="text" name="admin_username" class="form-control" required 
                       pattern="[a-zA-Z0-9_]{4,20}" title="4-20位字母、数字或下划线">
                <div class="form-text">4-20位字母、数字或下划线</div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">真实姓名</label>
                <input type="text" name="admin_name" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">邮箱</label>
                <input type="email" name="admin_email" class="form-control" required>
                <div class="form-text">用于接收系统通知和找回密码</div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">密码</label>
                <input type="password" name="admin_password" class="form-control" required 
                       pattern=".{8,}" title="至少8位字符" id="password">
                <div class="form-text">至少8位字符，建议包含字母和数字</div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">确认密码</label>
                <input type="password" name="admin_password_confirm" class="form-control" required id="passwordConfirm">
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <a href="?step=3" class="btn btn-secondary">上一步</a>
                <button type="submit" class="btn btn-success">开始安装</button>
            </div>
        </form>
        
        <script>
        document.getElementById('adminForm').addEventListener('submit', function(e) {
            var pass = document.getElementById('password').value;
            var confirm = document.getElementById('passwordConfirm').value;
            if (pass !== confirm) {
                e.preventDefault();
                alert('两次输入的密码不一致');
            }
        });
        </script>
        <?php
    }
    
    private function handleInstall() {
        $dbConfig = $_SESSION['db_config'] ?? null;
        $siteConfig = $_SESSION['site_config'] ?? null;
        
        if (!$dbConfig || !$siteConfig) {
            $_SESSION['error'] = '配置信息丢失，请重新安装';
            header('Location: ?step=1');
            exit;
        }
        
        $adminData = [
            'username' => $_POST['admin_username'] ?? '',
            'name' => $_POST['admin_name'] ?? '',
            'email' => $_POST['admin_email'] ?? '',
            'password' => $_POST['admin_password'] ?? ''
        ];
        
        try {
            // 1. 保存数据库配置
            $this->saveDatabaseConfig($dbConfig);
            
            // 2. 导入数据库结构
            $this->importDatabase($dbConfig);
            
            // 3. 创建管理员账号
            $this->createAdmin($dbConfig, $adminData);
            
            // 4. 保存站点配置
            $this->saveSiteConfig($dbConfig, $siteConfig);
            
            // 5. 创建锁文件
            file_put_contents(ROOT_PATH . 'install.lock', date('Y-m-d H:i:s'));
            
            // 清理session
            unset($_SESSION['db_config']);
            unset($_SESSION['site_config']);
            
            header('Location: ?step=5');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = '安装失败: ' . $e->getMessage();
            header('Location: ?step=4');
            exit;
        }
    }
    
    private function saveDatabaseConfig($config) {
        $content = "<?php\nreturn [\n";
        foreach ($config as $key => $value) {
            $content .= "    '{$key}' => " . var_export($value, true) . ",\n";
        }
        $content .= "];\n";
        
        file_put_contents(APP_PATH . 'config/database.php', $content);
    }
    
    private function importDatabase($config) {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
        $pdo = new PDO($dsn, $config['username'], $config['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $prefix = $config['prefix'];
        
        // 读取SQL文件并执行
        $sqlFile = ROOT_PATH . 'database/install.sql';
        if (file_exists($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            $sql = str_replace('gov_', $prefix, $sql);
            $pdo->exec($sql);
        }
    }
    
    private function createAdmin($config, $adminData) {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
        $pdo = new PDO($dsn, $config['username'], $config['password']);
        
        $prefix = $config['prefix'];
        $password = password_hash($adminData['password'], PASSWORD_BCRYPT);
        
        $stmt = $pdo->prepare("INSERT INTO {$prefix}admin (username, password, name, email, role_id, is_super, auth_status, status, create_time) VALUES (?, ?, ?, ?, 1, 1, 1, 1, NOW())");
        $stmt->execute([
            $adminData['username'],
            $password,
            $adminData['name'],
            $adminData['email']
        ]);
    }
    
    private function saveSiteConfig($config, $siteConfig) {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
        $pdo = new PDO($dsn, $config['username'], $config['password']);
        
        $prefix = $config['prefix'];
        
        // 保存站点配置到settings表
        $settings = [
            'site_title' => $siteConfig['title'],
            'site_subtitle' => $siteConfig['subtitle'],
            'site_url' => $siteConfig['url'],
            'icp_number' => $siteConfig['icp_number'],
            'police_number' => $siteConfig['police_number'],
            'copyright' => $siteConfig['copyright']
        ];
        
        $stmt = $pdo->prepare("INSERT INTO {$prefix}settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
        foreach ($settings as $key => $value) {
            $stmt->execute([$key, $value]);
        }
    }
    
    // 步骤5: 安装完成
    public function showComplete() {
        ?>
        <div class="text-center">
            <div class="success-icon mb-4">🎉</div>
            <h3>安装完成！</h3>
            <p class="text-muted">政府官网系统已成功安装</p>
            
            <div class="alert alert-warning">
                <strong>安全提示：</strong> 请删除 <code>public/install</code> 目录以确保安全
            </div>
            
            <div class="mt-4">
                <a href="/admin.php" class="btn btn-primary btn-lg me-2">进入后台管理</a>
                <a href="/" class="btn btn-outline-primary btn-lg">访问网站首页</a>
            </div>
            
            <div class="mt-4 text-start">
                <h5>后续操作：</h5>
                <ol>
                    <li>删除 <code>public/install</code> 目录</li>
                    <li>登录后台配置网站详细信息</li>
                    <li>配置SMTP邮箱（用于系统通知）</li>
                    <li>添加导航菜单和内容</li>
                </ol>
            </div>
        </div>
        <?php
    }
}

$installer = new GovInstaller();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>政府官网系统安装向导</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="install-container">
        <div class="install-header">
            <h1>🏛️ 政府官网系统</h1>
            <p class="text-muted">版本 v1.0 | 专业政务网站建设方案</p>
        </div>
        
        <!-- 进度条 -->
        <div class="progress-steps">
            <div class="step <?php echo $step >= 1 ? 'active' : ''; ?><?php echo $step > 1 ? 'completed' : ''; ?>">
                <span class="step-number">1</span>
                <span class="step-label">环境检测</span>
            </div>
            <div class="step <?php echo $step >= 2 ? 'active' : ''; ?><?php echo $step > 2 ? 'completed' : ''; ?>">
                <span class="step-number">2</span>
                <span class="step-label">数据库配置</span>
            </div>
            <div class="step <?php echo $step >= 3 ? 'active' : ''; ?><?php echo $step > 3 ? 'completed' : ''; ?>">
                <span class="step-number">3</span>
                <span class="step-label">站点设置</span>
            </div>
            <div class="step <?php echo $step >= 4 ? 'active' : ''; ?><?php echo $step > 4 ? 'completed' : ''; ?>">
                <span class="step-number">4</span>
                <span class="step-label">创建管理员</span>
            </div>
            <div class="step <?php echo $step >= 5 ? 'active' : ''; ?>">
                <span class="step-number">5</span>
                <span class="step-label">安装完成</span>
            </div>
        </div>
        
        <div class="install-content">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
            <?php endif; ?>
            
            <?php
            switch ($step) {
                case 1:
                    $installer->showEnvironmentCheck();
                    break;
                case 2:
                    $installer->showDatabaseConfig();
                    break;
                case 3:
                    $installer->showSiteConfig();
                    break;
                case 4:
                    $installer->showAdminConfig();
                    break;
                case 5:
                    $installer->showComplete();
                    break;
            }
            ?>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
