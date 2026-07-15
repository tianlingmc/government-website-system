<?php include APP_PATH . 'view/home/public/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">首页</a></li>
                    <li class="breadcrumb-item"><a href="/consult">咨询投诉</a></li>
                    <li class="breadcrumb-item active">详情</li>
                </ol>
            </nav>
            
            <!-- 咨询内容 -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>
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
                    </span>
                    <small class="text-muted">
                        <?php echo date('Y-m-d H:i', strtotime($consult['create_time'])); ?>
                    </small>
                </div>
                <div class="card-body">
                    <h4 class="card-title mb-4"><?php echo htmlspecialchars($consult['title']); ?></h4>
                    <div class="content">
                        <?php echo nl2br(htmlspecialchars($consult['content'])); ?>
                    </div>
                </div>
            </div>
            
            <!-- 回复内容 -->
            <?php if (!empty($consult['reply_content'])): ?>
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        <i class="bi bi-reply-fill"></i> 官方回复
                        <small class="float-end">
                            <?php echo date('Y-m-d H:i', strtotime($consult['reply_time'])); ?>
                        </small>
                    </div>
                    <div class="card-body">
                        <div class="content">
                            <?php echo nl2br(htmlspecialchars($consult['reply_content'])); ?>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    <i class="bi bi-clock"></i> 您的咨询正在处理中，请耐心等待回复
                </div>
            <?php endif; ?>
            
            <div class="mt-4">
                <a href="/consult" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> 返回列表
                </a>
                <?php if (!empty($_SESSION['user_id']) && $consult['user_id'] == $_SESSION['user_id']): ?>
                    <a href="/consult/my" class="btn btn-outline-secondary ms-2">
                        <i class="bi bi-person"></i> 我的咨询
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . 'view/home/public/footer.php'; ?>
