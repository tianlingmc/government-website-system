<?php 
$title = '裁判文书管理';
$pageTitle = '裁判文书管理';
$currentMenu = 'judicial';
include APP_PATH . 'view/admin/public/header.php'; 
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">裁判文书列表</h3>
        <div class="card-tools">
            <a href="/admin/judicial/add" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> 添加文书
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width: 60px">ID</th>
                    <th>案号</th>
                    <th>案件名称</th>
                    <th>法院</th>
                    <th style="width: 100px">类型</th>
                    <th style="width: 100px">状态</th>
                    <th style="width: 120px">判决日期</th>
                    <th style="width: 150px">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($list)): ?>
                    <?php foreach ($list as $item): ?>
                    <tr>
                        <td><?php echo $item['id']; ?></td>
                        <td><?php echo htmlspecialchars($item['case_no']); ?></td>
                        <td><?php echo htmlspecialchars($item['case_name']); ?></td>
                        <td><?php echo htmlspecialchars($item['court']); ?></td>
                        <td><?php echo htmlspecialchars($item['case_type']); ?></td>
                        <td>
                            <?php if ($item['status'] == 1): ?>
                                <span class="badge badge-success">公开</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">未公开</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $item['judge_date']; ?></td>
                        <td>
                            <a href="/admin/judicial/edit?id=<?php echo $item['id']; ?>" class="btn btn-info btn-sm">
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
                        <td colspan="8" class="text-center py-4">暂无数据</td>
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
    if (confirm('确定要删除这条裁判文书吗？')) {
        fetch('/admin/judicial/delete', {
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
