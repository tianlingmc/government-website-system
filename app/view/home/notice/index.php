<?php include APP_PATH . 'view/home/public/header.php'; ?>

<!-- 页面头部横幅 -->
<section class="page-banner bg-gradient-red">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center text-white py-5">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb justify-content-center breadcrumb-light">
                        <li class="breadcrumb-item"><a href="/" class="text-white-50">首页</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">政务公告</li>
                    </ol>
                </nav>
                <h1 class="page-banner-title">
                    <i class="fas fa-bullhorn me-2"></i>政务公告
                </h1>
                <p class="page-banner-desc opacity-90">及时了解政府最新动态和重要通知</p>
            </div>
        </div>
    </div>
</section>

<!-- 公告列表 -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <?php if (!empty($notices)): ?>
                    <div class="notice-list-modern">
                        <?php foreach ($notices as $index => $notice): ?>
                        <article class="notice-card" style="animation-delay: <?php echo $index * 0.1; ?>s">
                            <div class="notice-card-body">
                                <div class="notice-meta">
                                    <div class="notice-date-box">
                                        <span class="date-day"><?php echo date('d', strtotime($notice['publish_time'])); ?></span>
                                        <span class="date-month"><?php echo date('m月', strtotime($notice['publish_time'])); ?></span>
                                    </div>
                                    <div class="notice-tags">
                                        <?php if ($notice['is_top']): ?>
                                        <span class="badge bg-danger"><i class="fas fa-thumbtack me-1"></i>置顶</span>
                                        <?php endif; ?>
                                        <?php if ($notice['is_important']): ?>
                                        <span class="badge bg-warning text-dark"><i class="fas fa-exclamation me-1"></i>重要</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="notice-content">
                                    <h3 class="notice-title">
                                        <a href="/notice/detail/<?php echo $notice['id']; ?>"><?php echo htmlspecialchars($notice['title']); ?></a>
                                    </h3>
                                    <p class="notice-excerpt"><?php echo htmlspecialchars($notice['summary'] ?? mb_substr(strip_tags($notice['content']), 0, 150) . '...'); ?></p>
                                    <div class="notice-footer">
                                        <span class="notice-views"><i class="fas fa-eye me-1"></i><?php echo $notice['views'] ?? 0; ?> 阅读</span>
                                        <a href="/notice/detail/<?php echo $notice['id']; ?>" class="btn-read-more">
                                            阅读全文<i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </article>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- 分页 -->
                    <?php if ($totalPages > 1): ?>
                        <nav class="mt-5">
                            <ul class="pagination pagination-modern justify-content-center">
                                <?php if ($page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="/notice?page=<?php echo $page - 1; ?>">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                        <a class="page-link" href="/notice?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                                
                                <?php if ($page < $totalPages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="/notice?page=<?php echo $page + 1; ?>">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="empty-state-large">
                        <div class="empty-icon-large">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <h4>暂无公告</h4>
                        <p class="text-muted">目前还没有发布任何公告，请稍后再来查看</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include APP_PATH . 'view/home/public/footer.php'; ?>
