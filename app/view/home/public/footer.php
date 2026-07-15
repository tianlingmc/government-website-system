    <!-- 页脚 - 现代化设计 -->
    <footer class="main-footer mt-auto">
        <!-- 页脚顶部波浪 -->
        <div class="footer-wave">
            <svg viewBox="0 0 1440 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 50L48 45.7C96 41 192 33 288 33.3C384 33 480 41 576 50C672 59 768 67 864 66.7C960 67 1056 59 1152 50C1248 41 1344 33 1392 28.3L1440 25V100H1392C1344 100 1248 100 1152 100C1056 100 960 100 864 100C768 100 672 100 576 100C480 100 384 100 288 100C192 100 96 100 48 100H0V50Z" fill="rgba(220, 38, 38, 0.05)"/>
            </svg>
        </div>
        
        <div class="footer-content py-5">
            <div class="container">
                <div class="row g-4">
                    <!-- 品牌区域 -->
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-brand">
                            <div class="d-flex align-items-center mb-3">
                                <div class="footer-brand-icon">
                                    <?php if (!empty($siteConfig['site_logo'])): ?>
                                    <img src="<?php echo htmlspecialchars($siteConfig['site_logo']); ?>" alt="logo">
                                    <?php else: ?>
                                    <i class="fas fa-landmark"></i>
                                    <?php endif; ?>
                                </div>
                                <h5 class="mb-0 ms-2"><?php echo htmlspecialchars($siteConfig['site_title'] ?? '政府官网'); ?></h5>
                            </div>
                            <p class="footer-desc"><?php echo htmlspecialchars($siteConfig['site_subtitle'] ?? '政务服务门户网站'); ?></p>
                            <div class="footer-social d-flex gap-2 mb-3">
                                <a href="#" class="social-link" aria-label="微信"><i class="fab fa-weixin"></i></a>
                                <a href="#" class="social-link" aria-label="微博"><i class="fab fa-weibo"></i></a>
                                <a href="#" class="social-link" aria-label="邮箱"><i class="fas fa-envelope"></i></a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 快速链接 -->
                    <div class="col-lg-2 col-md-6">
                        <h6 class="footer-title">快速链接</h6>
                        <ul class="footer-links list-unstyled">
                            <li><a href="/notice"><i class="fas fa-chevron-right me-1"></i>政务公开</a></li>
                            <li><a href="/policy"><i class="fas fa-chevron-right me-1"></i>政策法规</a></li>
                            <li><a href="/service"><i class="fas fa-chevron-right me-1"></i>公众服务</a></li>
                            <li><a href="/judicial"><i class="fas fa-chevron-right me-1"></i>裁判文书</a></li>
                            <li><a href="/contact"><i class="fas fa-chevron-right me-1"></i>联系我们</a></li>
                        </ul>
                    </div>
                    
                    <!-- 服务指南 -->
                    <div class="col-lg-3 col-md-6">
                        <h6 class="footer-title">服务指南</h6>
                        <ul class="footer-links list-unstyled">
                            <li><a href="/service/guide"><i class="fas fa-chevron-right me-1"></i>办事指南</a></li>
                            <li><a href="/service/consult"><i class="fas fa-chevron-right me-1"></i>在线咨询</a></li>
                            <li><a href="/search"><i class="fas fa-chevron-right me-1"></i>站内搜索</a></li>
                            <li><a href="/sitemap"><i class="fas fa-chevron-right me-1"></i>网站地图</a></li>
                        </ul>
                    </div>
                    
                    <!-- 联系方式 -->
                    <div class="col-lg-3 col-md-6">
                        <h6 class="footer-title">联系方式</h6>
                        <ul class="footer-contact list-unstyled">
                            <li><i class="fas fa-map-marker-alt"></i><span><?php echo htmlspecialchars($siteConfig['contact_address'] ?? '某某市政府大楼'); ?></span></li>
                            <li><i class="fas fa-phone"></i><span><?php echo htmlspecialchars($siteConfig['contact_phone'] ?? '12345 政务服务热线'); ?></span></li>
                            <li><i class="fas fa-envelope"></i><span><?php echo htmlspecialchars($siteConfig['contact_email'] ?? 'contact@gov.cn'); ?></span></li>
                            <li><i class="fas fa-clock"></i><span><?php echo htmlspecialchars($siteConfig['contact_work_time'] ?? '周一至周五 9:00-17:00'); ?></span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 页脚底部版权 -->
        <div class="footer-bottom py-3">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                        <p class="mb-0"><?php echo htmlspecialchars($siteConfig['copyright'] ?? '© 2026 版权所有'); ?></p>
                        <?php if (!empty($siteConfig['icp_number'])): ?>
                        <small class="d-block mt-1 opacity-75"><?php echo htmlspecialchars($siteConfig['icp_number']); ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <div class="footer-bottom-links">
                            <a href="/admin" class="me-3"><i class="fas fa-cog me-1"></i>管理后台</a>
                            <a href="#" class="me-3">隐私政策</a>
                            <a href="#">使用条款</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- 返回顶部按钮 -->
    <button type="button" class="btn btn-back-to-top" id="backToTop" aria-label="返回顶部">
        <i class="fas fa-arrow-up"></i>
    </button>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- 懒加载脚本 -->
    <script src="/assets/js/lazyload.js"></script>
    <!-- 主脚本 -->
    <script src="/assets/js/main.js"></script>
    
    <!-- 返回顶部脚本 -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var backToTopBtn = document.getElementById('backToTop');
        
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopBtn.classList.add('show');
            } else {
                backToTopBtn.classList.remove('show');
            }
        });
        
        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });
    </script>
</body>
</html>
