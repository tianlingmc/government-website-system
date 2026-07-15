<?php include APP_PATH . 'view/home/public/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">首页</a></li>
                    <li class="breadcrumb-item"><a href="/notice">政务公告</a></li>
                    <li class="breadcrumb-item active" aria-current="page">公告详情</li>
                </ol>
            </nav>
            
            <article class="card">
                <div class="card-body">
                    <h1 class="card-title h3 mb-4"><?php echo htmlspecialchars($notice['title']); ?></h1>
                    
                    <div class="text-muted mb-4">
                        <small>
                            <i class="bi bi-calendar"></i> <?php echo date('Y-m-d H:i', strtotime($notice['create_time'])); ?>
                            <span class="mx-2">|</span>
                            <i class="bi bi-eye"></i> <?php echo $notice['views']; ?> 阅读
                        </small>
                    </div>
                    
                    <div class="content">
                        <?php echo $notice['content']; ?>
                    </div>
                </div>
            </article>
            
            <div class="mt-4">
                <a href="/notice" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> 返回公告列表
                </a>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . 'view/home/public/footer.php'; ?>
