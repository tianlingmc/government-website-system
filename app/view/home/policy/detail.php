<?php include APP_PATH . 'view/home/public/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">首页</a></li>
                    <li class="breadcrumb-item"><a href="/policy">政策法规</a></li>
                    <li class="breadcrumb-item active">详情</li>
                </ol>
            </nav>
            
            <article class="card">
                <div class="card-body">
                    <h1 class="card-title h3 mb-4"><?php echo htmlspecialchars($policy['title']); ?></h1>
                    
                    <div class="text-muted mb-4">
                        <small>
                            <i class="bi bi-calendar"></i> <?php echo date('Y-m-d', strtotime($policy['publish_date'])); ?>
                            <?php if (!empty($policy['department'])): ?>
                                <span class="mx-2">|</span>
                                <i class="bi bi-building"></i> <?php echo htmlspecialchars($policy['department']); ?>
                            <?php endif; ?>
                        </small>
                    </div>
                    
                    <div class="content">
                        <?php echo $policy['content']; ?>
                    </div>
                    
                    <?php if (!empty($policy['attachment'])): ?>
                        <div class="mt-4 p-3 bg-light rounded">
                            <h6><i class="fas fa-paperclip me-2"></i>附件下载</h6>
                            <a href="<?php echo htmlspecialchars($policy['attachment']); ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                                <i class="fas fa-download me-1"></i>下载附件
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </article>
            
            <div class="mt-4">
                <a href="/policy" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> 返回列表
                </a>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . 'view/home/public/footer.php'; ?>
