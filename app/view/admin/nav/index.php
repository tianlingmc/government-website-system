<?php include APP_PATH . 'view/admin/public/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include APP_PATH . 'view/admin/public/sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">导航管理</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="/admin/nav/add" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> 添加导航
                    </a>
                </div>
            </div>
            
            <!-- 导航列表 -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="60">排序</th>
                                    <th>导航名称</th>
                                    <th>链接地址</th>
                                    <th>打开方式</th>
                                    <th>状态</th>
                                    <th width="150">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($navs as $nav): ?>
                                <tr>
                                    <td><?= $nav['sort'] ?></td>
                                    <td>
                                        <?= str_repeat('　　', $nav['parent_id'] > 0 ? 1 : 0) ?>
                                        <?php if ($nav['icon']): ?>
                                        <i class="bi bi-<?= $nav['icon'] ?>"></i>
                                        <?php endif; ?>
                                        <?= htmlspecialchars($nav['nav_name']) ?>
                                    </td>
                                    <td><?= htmlspecialchars($nav['nav_url']) ?></td>
                                    <td><?= $nav['target'] === '_blank' ? '新窗口' : '当前窗口' ?></td>
                                    <td>
                                        <?php if ($nav['is_show']): ?>
                                        <span class="badge bg-success">显示</span>
                                        <?php else: ?>
                                        <span class="badge bg-secondary">隐藏</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="/admin/nav/edit?id=<?= $nav['id'] ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteNav(<?= $nav['id'] ?>)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if (empty($navs)): ?>
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-1"></i>
                        <p>暂无导航数据</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
function deleteNav(id) {
    if (confirm('确定要删除这个导航吗？')) {
        fetch('/admin/nav/delete', {
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
