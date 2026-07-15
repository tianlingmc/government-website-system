<?php include APP_PATH . 'view/home/public/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="mb-4">办事指南</h1>
            
            <div class="accordion" id="guideAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#guide1">
                            如何查询政务公告？
                        </button>
                    </h2>
                    <div id="guide1" class="accordion-collapse collapse show" data-bs-parent="#guideAccordion">
                        <div class="accordion-body">
                            您可以通过以下方式查询政务公告：
                            <ol>
                                <li>点击网站顶部导航栏的"政务公开"</li>
                                <li>在公告列表中浏览最新公告</li>
                                <li>使用搜索功能快速查找</li>
                            </ol>
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#guide2">
                            如何检索裁判文书？
                        </button>
                    </h2>
                    <div id="guide2" class="accordion-collapse collapse" data-bs-parent="#guideAccordion">
                        <div class="accordion-body">
                            裁判文书检索步骤：
                            <ol>
                                <li>点击导航栏的"裁判文书"</li>
                                <li>在搜索框输入案件名称、案号或关键词</li>
                                <li>点击"检索"按钮查看结果</li>
                            </ol>
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#guide3">
                            如何提交在线咨询？
                        </button>
                    </h2>
                    <div id="guide3" class="accordion-collapse collapse" data-bs-parent="#guideAccordion">
                        <div class="accordion-body">
                            提交咨询的步骤：
                            <ol>
                                <li>进入"公众服务"页面</li>
                                <li>点击"在线咨询"</li>
                                <li>填写咨询表单并提交</li>
                                <li>我们会尽快回复您</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . 'view/home/public/footer.php'; ?>
