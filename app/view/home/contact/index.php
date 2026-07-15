<?php include APP_PATH . 'view/home/public/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">首页</a></li>
                    <li class="breadcrumb-item active" aria-current="page">联系我们</li>
                </ol>
            </nav>
            <h1 class="page-title mb-4">
                <i class="fas fa-envelope me-2"></i>联系我们
            </h1>
            
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100 contact-card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h5 class="card-title">办公地址</h5>
                            <p class="card-text"><?php echo nl2br(htmlspecialchars($contact['address'] ?? '暂无信息')); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100 contact-card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <h5 class="card-title">联系电话</h5>
                            <p class="card-text"><?php echo htmlspecialchars($contact['phone'] ?? '暂无信息'); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100 contact-card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h5 class="card-title">电子邮箱</h5>
                            <p class="card-text"><?php echo htmlspecialchars($contact['email'] ?? '暂无信息'); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100 contact-card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="contact-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h5 class="card-title">工作时间</h5>
                            <p class="card-text"><?php echo nl2br(htmlspecialchars($contact['work_time'] ?? '周一至周五 9:00-17:00')); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . 'view/home/public/footer.php'; ?>
