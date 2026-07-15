<?php include APP_PATH . 'view/home/public/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">首页</a></li>
                    <li class="breadcrumb-item active" aria-current="page">公众服务</li>
                </ol>
            </nav>
            <h1 class="page-title mb-4">
                <i class="fas fa-tasks me-2"></i>公众服务
            </h1>
            
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100 service-card border-0 shadow-sm">
                        <div class="card-body text-center p-5">
                            <div class="service-icon mb-3">
                                <i class="fas fa-book fa-3x"></i>
                            </div>
                            <h5 class="card-title">办事指南</h5>
                            <p class="card-text text-muted">了解各类政务服务的办理流程和所需材料</p>
                            <a href="/service/guide" class="btn btn-outline-primary">查看指南</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 service-card border-0 shadow-sm">
                        <div class="card-body text-center p-5">
                            <div class="service-icon mb-3">
                                <i class="fas fa-comments fa-3x"></i>
                            </div>
                            <h5 class="card-title">在线咨询</h5>
                            <p class="card-text text-muted">有问题？提交咨询，我们会尽快回复您</p>
                            <a href="/service/consult" class="btn btn-outline-primary">我要咨询</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . 'view/home/public/footer.php'; ?>
