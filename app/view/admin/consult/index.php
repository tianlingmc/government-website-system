<?php 
$title = '咨询投诉';
$pageTitle = '咨询投诉管理';
$currentMenu = 'consult';
include APP_PATH . 'view/admin/public/header.php'; 
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">咨询列表</h3>
        <div class="card-tools">
            <div class="btn-group">
                <a href="/admin/consult" class="btn btn-default btn-sm <?php echo $status === '' ? 'active' : ''; ?>">全部</a>
                <a href="/admin/consult?status=0" class="btn btn-warning btn-sm <?php echo $status === '0' ? 'active' : ''; ?>">待处理</a>
                <a href="/admin/consult?status=1" class="btn btn-info btn-sm <?php echo $status === '1' ? 'active' : ''; ?>">已查看</a>
                <a href="/admin/consult?status=2" class="btn btn-success btn-sm <?php echo $status === '2' ? 'active' : ''; ?>">已回复</a>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width: 60px">ID</th>
                    <th>姓名</th>
                    <th>联系方式</th>
                    <th>咨询内容</th>
                    <th style="width: 100px">状态</th>
                    <th style="width: 150px">提交时间</th>
                    <th style="width: 120px">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($list)): ?>
                    <?php foreach ($list as $item): ?>
                    <tr>
                        <td><?php echo $item['id']; ?></td>
                        <td><?php echo htmlspecialchars($item['contact_name'] ?? '-'); ?></td>
                        <td>
                            <?php echo htmlspecialchars($item['contact_phone'] ?? '-'); ?><br>
                            <small class="text-muted"><?php echo htmlspecialchars($item['contact_email'] ?? '-'); ?></small>
                        </td>
                        <td><?php echo mb_substr(htmlspecialchars($item['content']), 0, 50) . '...'; ?></td>
                        <td>
                            <?php if ($item['status'] == 0): ?>
                                <span class="badge badge-warning">待处理</span>
                            <?php elseif ($item['status'] == 1): ?>
                                <span class="badge badge-info">已查看</span>
                            <?php else: ?>
                                <span class="badge badge-success">已回复</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $item['create_time']; ?></td>
                        <td>
                            <a href="/admin/consult/view?id=<?php echo $item['id']; ?>" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> 查看
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">暂无数据</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if ($totalPage > 1): ?>
    <div class="card-footer clearfix">
        <ul class="pagination pagination-sm m-0 float-right">
            <?php if ($page > 1): ?>
            <li class="page-item"><a class="page-link" href="?page=<?php echo $page-1; ?><?php echo $status !== '' ? '&status='.$status : ''; ?>">上一页</a></li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPage; $i++): ?>
            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?><?php echo $status !== '' ? '&status='.$status : ''; ?>"><?php echo $i; ?></a>
            </li>
            <?php endfor; ?>
            <?php if ($page < $totalPage): ?>
            <li class="page-item"><a class="page-link" href="?page=<?php echo $page+1; ?><?php echo $status !== '' ? '&status='.$status : ''; ?>">下一页</a></li>
            <?php endif; ?>
        </ul>
    </div>
    <?php endif; ?>
</div>

<?php include APP_PATH . 'view/admin/public/footer.php'; ?>
