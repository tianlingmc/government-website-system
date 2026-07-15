<?php include APP_PATH . 'view/home/public/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">首页</a></li>
                    <li class="breadcrumb-item active">咨询投诉</li>
                </ol>
            </nav>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">咨询投诉</h1>
                <div>
                    <?php if (!empty($_SESSION['user_id'])): ?>
                        <a href="/consult/my" class="btn btn-outline-primary btn-sm me-2">
                            <i class="bi bi-person"></i> 我的咨询
                        </a>
                        <a href="/consult/submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus"></i> 我要咨询
                        </a>
                    <?php else: ?>
                        <a href="/login" class="btn btn-primary btn-sm">
                            <i class="bi bi-box-arrow-in-right"></i> 登录后咨询
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- 咨询列表 -->
            <?php if (!empty($consults)): ?>
                <div class="list-group">
                    <?php foreach ($consults as $consult): ?>
                        <div class="list-group-item list-group-item-action">
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
                            <p class="mb-1 text-muted">
                                <?php echo mb_substr(strip_tags($consult['content']), 0, 100) . '...'; ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="badge bg-<?php echo $consult['type'] == 1 ? 'info' : 'warning'; ?>">
                                    <?php echo $consult['type'] == 1 ? '咨询' : '投诉'; ?>
                                </span>
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> 已回复
                                </span>
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
                                    <a class="page-link" href="/consult?page=<?php echo $page - 1; ?>">上一页</a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="/consult?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($page < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="/consult?page=<?php echo $page + 1; ?>">下一页</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                    <p class="mt-3 text-muted">暂无公开的咨询记录</p>
                    <?php if (!empty($_SESSION['user_id'])): ?>
                        <a href="/consult/submit" class="btn btn-primary mt-2">
                            <i class="bi bi-plus"></i> 我要咨询
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include APP_PATH . 'view/home/public/footer.php'; ?>
