<?php 
$title = '系统信息';
$pageTitle = '系统信息';
$currentMenu = 'settings';
$breadcrumb = [
    ['name' => '网站设置', 'url' => '/admin/settings'],
    ['name' => '系统信息']
];
include APP_PATH . 'view/admin/public/header.php'; 
?>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">服务器信息</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td style="width: 150px">PHP版本</td>
                            <td><?php echo $info['php_version']; ?></td>
                        </tr>
                        <tr>
                            <td>操作系统</td>
                            <td><?php echo $info['os']; ?></td>
                        </tr>
                        <tr>
                            <td>Web服务器</td>
                            <td><?php echo $info['server_software']; ?></td>
                        </tr>
                        <tr>
                            <td>数据库版本</td>
                            <td><?php echo $info['database_version']; ?></td>
                        </tr>
                        <tr>
                            <td>安装时间</td>
                            <td><?php echo $info['install_time']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">PHP配置</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td style="width: 200px">最大上传文件大小</td>
                            <td><?php echo $info['upload_max_filesize']; ?></td>
                        </tr>
                        <tr>
                            <td>最大执行时间</td>
                            <td><?php echo $info['max_execution_time']; ?> 秒</td>
                        </tr>
                        <tr>
                            <td>内存限制</td>
                            <td><?php echo $info['memory_limit']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h3 class="card-title">系统维护</h3>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-warning" onclick="clearCache()">
                    <i class="fas fa-broom"></i> 清除缓存
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function clearCache() {
    if (confirm('确定要清除系统缓存吗？')) {
        fetch('/admin/settings/clearCache', {
            method: 'POST'
        })
        .then(res => res.json())
        .then(data => {
            if (data.code === 200) {
                alert('缓存清除成功');
            } else {
                alert(data.message);
            }
        });
    }
}
</script>

<?php include APP_PATH . 'view/admin/public/footer.php'; ?>
