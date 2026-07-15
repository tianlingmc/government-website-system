<?php
// 获取当前分组
$currentGroup = $group ?? 'common';
$groups = $groups ?? [];
$configs = $configs ?? [];
?>
<?php include APP_PATH . 'view/admin/public/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include APP_PATH . 'view/admin/public/sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">文字配置</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="/admin/language/add" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> 添加配置
                    </a>
                </div>
            </div>
            
            <!-- 分组标签 -->
            <ul class="nav nav-tabs mb-4">
                <?php foreach ($groups as $key => $name): ?>
                <li class="nav-item">
                    <a class="nav-link <?= $currentGroup === $key ? 'active' : '' ?>" href="/admin/language?group=<?= $key ?>">
                        <?= htmlspecialchars($name) ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
            
            <!-- 配置列表 -->
            <div class="card">
                <div class="card-body">
                    <form action="/admin/language/save" method="post">
                        <?php foreach ($configs as $config): ?>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">
                                <?= htmlspecialchars($config['lang_key']) ?>
                                <small class="text-muted d-block"><?= htmlspecialchars($config['module']) ?></small>
                            </label>
                            <div class="col-sm-7">
                                <textarea name="configs[<?= $config['lang_key'] ?>]" class="form-control" rows="2"><?= htmlspecialchars($config['lang_value'] ?? '') ?></textarea>
                            </div>
                            <div class="col-sm-2">
                                <a href="/admin/language/edit?id=<?= $config['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteConfig(<?= $config['id'] ?>)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if (!empty($configs)): ?>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">保存配置</button>
                        </div>
                        <?php else: ?>
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-1"></i>
                            <p>该分组下暂无配置项</p>
                        </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
function deleteConfig(id) {
    if (confirm('确定要删除这个配置项吗？')) {
        fetch('/admin/language/delete', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'id=' + id
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
