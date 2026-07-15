<?php include APP_PATH . 'view/home/public/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">首页</a></li>
                    <li class="breadcrumb-item"><a href="/judicial">裁判文书</a></li>
                    <li class="breadcrumb-item active">详情</li>
                </ol>
            </nav>
            
            <article class="card">
                <div class="card-body">
                    <h1 class="card-title h3 mb-4"><?php echo htmlspecialchars($judicial['case_name']); ?></h1>
                    
                    <div class="row mb-4 text-muted">
                        <div class="col-md-6">
                            <p><strong>案号：</strong><?php echo htmlspecialchars($judicial['case_no']); ?></p>
                            <p><strong>法院：</strong><?php echo htmlspecialchars($judicial['court']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>裁判日期：</strong><?php echo date('Y-m-d', strtotime($judicial['judge_date'])); ?></p>
                            <p><strong>案件类型：</strong><?php echo htmlspecialchars($judicial['case_type'] ?? '民事'); ?></p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="content">
                        <?php echo nl2br(htmlspecialchars($judicial['content'])); ?>
                    </div>
                </div>
            </article>
            
            <div class="mt-4">
                <a href="/judicial" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> 返回列表
                </a>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . 'view/home/public/footer.php'; ?>
