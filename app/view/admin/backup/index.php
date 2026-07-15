<?php include APP_PATH . 'view/admin/public/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include APP_PATH . 'view/admin/public/sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">数据备份</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <button type="button" class="btn btn-primary" onclick="createBackup()">
                        <i class="bi bi-plus-lg"></i> 创建备份
                    </button>
                </div>
            </div>
            
            <!-- 备份列表 -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>文件名</th>
                                    <th>大小</th>
                                    <th>创建时间</th>
                                    <th width="200">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($backups as $backup): ?>
                                <tr>
                                    <td><?= htmlspecialchars($backup['filename']) ?></td>
                                    <td><?= formatSize($backup['size']) ?></td>
                                    <td><?= $backup['created_at'] ?></td>
                                    <td>
                                        <a href="/admin/backup/download?file=<?= urlencode($backup['filename']) ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-download"></i> 下载
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-success" onclick="restoreBackup('<?= $backup['filename'] ?>')">
                                            <i class="bi bi-arrow-counterclockwise"></i> 恢复
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteBackup('<?= $backup['filename'] ?>')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if (empty($backups)): ?>
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-1"></i>
                        <p>暂无备份文件</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
function createBackup() {
    if (confirm('确定要创建新的数据库备份吗？')) {
        fetch('/admin/backup/create', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'type=full'
        }).then(r => r.json()).then(res => {
            if (res.code === 200) {
                location.reload();
            } else {
                alert(res.message);
            }
        });
    }
}

function restoreBackup(filename) {
    if (confirm('确定要恢复这个备份吗？当前数据将被覆盖！')) {
        fetch('/admin/backup/restore', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'file=' + encodeURIComponent(filename)
        }).then(r => r.json()).then(res => {
            if (res.code === 200) {
                alert('恢复成功');
            } else {
                alert(res.message);
            }
        });
    }
}

function deleteBackup(filename) {
    if (confirm('确定要删除这个备份文件吗？')) {
        fetch('/admin/backup/delete', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'file=' + encodeURIComponent(filename)
        }).then(r => r.json()).then(res => {
            if (res.code === 200) {
                location.reload();
            } else {
                alert(res.message);
            }
        });
    }
}
</script>

<?php include APP_PATH . 'view/admin/public/footer.php'; ?>

<?php
function formatSize($size) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $unitIndex = 0;
    while ($size >= 1024 && $unitIndex < count($units) - 1) {
        $size /= 1024;
        $unitIndex++;
    }
    return round($size, 2) . ' ' . $units[$unitIndex];
}
?>
