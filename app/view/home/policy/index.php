<?php include APP_PATH . 'view/home/public/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">首页</a></li>
                    <li class="breadcrumb-item active" aria-current="page">政策法规</li>
                </ol>
            </nav>
            <h1 class="page-title mb-4">
                <i class="fas fa-file-alt me-2"></i>政策法规
            </h1>
            
            <?php if (!empty($policies)): ?>
                <div class="list-group">
                    <?php foreach ($policies as $policy): ?>
                        <a href="/policy/detail/<?php echo $policy['id']; ?>" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1"><?php echo htmlspecialchars($policy['title']); ?></h5>
                                <small class="text-muted"><?php echo date('Y-m-d', strtotime($policy['publish_date'])); ?></small>
                            </div>
                            <p class="mb-1 text-muted">
                                <small><i class="fas fa-building me-1"></i><?php echo htmlspecialchars($policy['publish_org'] ?? ''); ?></small>
                            </p>
                        </a>
                    <?php endforeach; ?>
                </div>
                
                <!-- 分页 -->
                <?php if ($totalPages > 1): ?>
                    <nav class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="/policy?page=<?php echo $page - 1; ?>">上一页</a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="/policy?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($page < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="/policy?page=<?php echo $page + 1; ?>">下一页</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php else: ?>
                <div class="alert alert-info">暂无政策法规</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include APP_PATH . 'view/home/public/footer.php'; ?>
