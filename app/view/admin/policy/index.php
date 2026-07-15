<?php 
$title = '政策法规';
$pageTitle = '政策法规';
$currentMenu = 'policy';
include APP_PATH . 'view/admin/public/header.php'; 
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">政策列表</h3>
        <div class="card-tools">
            <a href="/admin/policy/add" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> 添加政策
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width: 60px">ID</th>
                    <th>标题</th>
                    <th>发布部门</th>
                    <th style="width: 100px">状态</th>
                    <th style="width: 120px">发布日期</th>
                    <th style="width: 150px">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($list)): ?>
                    <?php foreach ($list as $item): ?>
                    <tr>
                        <td><?php echo $item['id']; ?></td>
                        <td><?php echo htmlspecialchars($item['title']); ?></td>
                        <td><?php echo htmlspecialchars($item['department'] ?? '-'); ?></td>
                        <td>
                            <?php if ($item['status'] == 1): ?>
                                <span class="badge badge-success">正常</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">禁用</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $item['publish_date']; ?></td>
                        <td>
                            <a href="/admin/policy/edit?id=<?php echo $item['id']; ?>" class="btn btn-info btn-sm">
                                <i class="fas fa-edit"></i> 编辑
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteItem(<?php echo $item['id']; ?>)">
                                <i class="fas fa-trash"></i> 删除
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">暂无数据</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if ($totalPage > 1): ?>
    <div class="card-footer clearfix">
        <ul class="pagination pagination-sm m-0 float-right">
            <?php if ($page > 1): ?>
            <li class="page-item"><a class="page-link" href="?page=<?php echo $page-1; ?>">上一页</a></li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPage; $i++): ?>
            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
            <?php endfor; ?>
            <?php if ($page < $totalPage): ?>
            <li class="page-item"><a class="page-link" href="?page=<?php echo $page+1; ?>">下一页</a></li>
            <?php endif; ?>
        </ul>
    </div>
    <?php endif; ?>
</div>

<script>
function deleteItem(id) {
    if (confirm('确定要删除这条政策吗？')) {
        fetch('/admin/policy/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + id
        })
        .then(res => res.json())
        .then(data => {
            if (data.code === 200) {
                location.reload();
            } else {
                alert(data.message);
            }
        });
    }
}
</script>

<?php include APP_PATH . 'view/admin/public/footer.php'; ?>
