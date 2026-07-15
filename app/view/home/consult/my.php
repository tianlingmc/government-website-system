<?php include APP_PATH . 'view/home/public/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">首页</a></li>
                    <li class="breadcrumb-item"><a href="/consult">咨询投诉</a></li>
                    <li class="breadcrumb-item active">我的咨询</li>
                </ol>
            </nav>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">我的咨询</h1>
                <a href="/consult/submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus"></i> 我要咨询
                </a>
            </div>
            
            <!-- 咨询列表 -->
            <?php if (!empty($consults)): ?>
                <div class="list-group">
                    <?php foreach ($consults as $consult): ?>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between mb-2">
                                <h5 class="mb-1">
                                    <a href="/consult/detail/<?php echo $consult['id']; ?>" class="text-decoration-none">
                                        <?php echo htmlspecialchars($consult['title']); ?>
                                    </a>
                                </h5>
                                <small class="text-muted">
                                    <?php echo date('Y-m-d', strtotime($consult['create_time'])); ?>
                                </small>
                            </div>
                            <p class="mb-2 text-muted">
                                <?php echo mb_substr(strip_tags($consult['content']), 0, 100) . '...'; ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-<?php echo $consult['type'] == 1 ? 'info' : 'warning'; ?> me-2">
                                        <?php echo $consult['type'] == 1 ? '咨询' : '投诉'; ?>
                                    </span>
                                    <?php if ($consult['is_public'] == 1): ?>
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-globe"></i> 公开
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-dark">
                                            <i class="bi bi-lock"></i> 私密
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <?php if ($consult['status'] == 0): ?>
                                        <span class="badge bg-warning text-dark">待处理</span>
                                    <?php elseif ($consult['status'] == 1): ?>
                                        <span class="badge bg-info">处理中</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i> 已回复
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- 分页 -->
                <?php if ($totalPages > 1): ?>
                    <nav class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="/consult/my?page=<?php echo $page - 1; ?>">上一页</a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="/consult/my?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($page < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="/consult/my?page=<?php echo $page + 1; ?>">下一页</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                    <p class="mt-3 text-muted">您还没有提交过咨询</p>
                    <a href="/consult/submit" class="btn btn-primary mt-2">
                        <i class="bi bi-plus"></i> 我要咨询
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include APP_PATH . 'view/home/public/footer.php'; ?>
