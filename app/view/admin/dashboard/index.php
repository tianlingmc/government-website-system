<?php 
$title = '控制台';
$pageTitle = '控制台';
$currentMenu = 'dashboard';
include APP_PATH . 'view/admin/public/header.php'; 
?>

<!-- 统计卡片 -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?php echo $stats['notice_count']; ?></h3>
                <p>政务公告</p>
            </div>
            <div class="icon">
                <i class="fas fa-bullhorn"></i>
            </div>
            <a href="/admin/notice" class="small-box-footer">管理 <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?php echo $stats['policy_count']; ?></h3>
                <p>政策法规</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <a href="/admin/policy" class="small-box-footer">管理 <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?php echo $stats['judicial_count']; ?></h3>
                <p>裁判文书</p>
            </div>
            <div class="icon">
                <i class="fas fa-gavel"></i>
            </div>
            <a href="/admin/judicial" class="small-box-footer">管理 <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?php echo $stats['user_count']; ?></h3>
                <p>注册用户</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="/admin/user" class="small-box-footer">管理 <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <!-- 最新公告 -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">最新公告</h3>
                <div class="card-tools">
                    <a href="/admin/notice" class="btn btn-tool">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php if (!empty($latestNotices)): ?>
                        <?php foreach ($latestNotices as $notice): ?>
                        <a href="/admin/notice/edit?id=<?php echo $notice['id']; ?>" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1 text-truncate" style="max-width: 300px;">
                                    <?php if ($notice['is_top']): ?><span class="badge badge-danger">置顶</span><?php endif; ?>
                                    <?php echo htmlspecialchars($notice['title']); ?>
                                </h6>
                                <small class="text-muted"><?php echo date('m-d', strtotime($notice['publish_time'])); ?></small>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="list-group-item text-center text-muted py-4">暂无公告</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- 待处理咨询 -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    待处理咨询
                    <?php if ($stats['consult_pending'] > 0): ?>
                    <span class="badge badge-danger"><?php echo $stats['consult_pending']; ?></span>
                    <?php endif; ?>
                </h3>
                <div class="card-tools">
                    <a href="/admin/consult" class="btn btn-tool">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php if (!empty($pendingConsults)): ?>
                        <?php foreach ($pendingConsults as $consult): ?>
                        <a href="/admin/consult/view?id=<?php echo $consult['id']; ?>" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1 text-truncate" style="max-width: 300px;">
                                    <?php echo htmlspecialchars($consult['name']); ?>
                                </h6>
                                <small class="text-muted"><?php echo date('m-d', strtotime($consult['create_time'])); ?></small>
                            </div>
                            <p class="mb-1 text-muted small text-truncate"><?php echo htmlspecialchars($consult['content']); ?></p>
                        </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="list-group-item text-center text-muted py-4">暂无待处理咨询</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . 'view/admin/public/footer.php'; ?>
