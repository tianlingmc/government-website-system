<?php include APP_PATH . 'view/admin/public/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include APP_PATH . 'view/admin/public/sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">日志详情</h1>
                <a href="/admin/log" class="btn btn-secondary">返回</a>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="150">ID</th>
                            <td><?= $log['id'] ?></td>
                        </tr>
                        <tr>
                            <th>管理员</th>
                            <td><?= htmlspecialchars($log['admin_name']) ?></td>
                        </tr>
                        <tr>
                            <th>操作类型</th>
                            <td><span class="badge bg-primary"><?= $log['type_text'] ?></span></td>
                        </tr>
                        <tr>
                            <th>操作内容</th>
                            <td><?= htmlspecialchars($log['content']) ?></td>
                        </tr>
                        <tr>
                            <th>IP地址</th>
                            <td><?= $log['ip'] ?></td>
                        </tr>
                        <tr>
                            <th>User Agent</th>
                            <td style="word-break: break-all;"><?= htmlspecialchars($log['user_agent']) ?></td>
                        </tr>
                        <tr>
                            <th>操作时间</th>
                            <td><?= $log['created_at'] ?></td>
                        </tr>
                        <?php if ($log['data'] && $log['data'] !== '[]'): ?>
                        <tr>
                            <th>附加数据</th>
                            <td><pre class="mb-0"><?= htmlspecialchars($log['data']) ?></pre></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include APP_PATH . 'view/admin/public/footer.php'; ?>
