/**
 * 图片懒加载
 * 支持高斯模糊过渡效果
 */

(function() {
    'use strict';
    
    // 配置
    const config = {
        rootMargin: '50px 0px',
        threshold: 0.01,
        placeholderColor: '#f0f0f0'
    };
    
    // 检查浏览器是否支持 IntersectionObserver
    if (!('IntersectionObserver' in window)) {
        // 不支持则直接加载所有图片
        loadAllImages();
        return;
    }
    
    // 创建观察器
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                loadImage(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, config);
    
    // 加载图片
    function loadImage(img) {
        const src = img.dataset.src;
        if (!src) return;
        
        // 创建临时图片预加载
        const tempImg = new Image();
        
        tempImg.onload = function() {
            img.src = src;
            img.classList.add('lazyloaded');
            img.classList.remove('lazyload', 'lazyloading');
            
            // 触发加载完成事件
            img.dispatchEvent(new CustomEvent('lazyloaded', {
                detail: { src: src }
            }));
        };
        
        tempImg.onerror = function() {
            // 加载失败，显示占位图
            img.classList.add('lazyerror');
            console.warn('Image load failed:', src);
        };
        
        tempImg.src = src;
    }
    
    // 加载所有图片（用于不支持 IntersectionObserver 的浏览器）
    function loadAllImages() {
        const images = document.querySelectorAll('img[data-src]');
        images.forEach(loadImage);
    }
    
    // 初始化懒加载
    function initLazyLoad() {
        const images = document.querySelectorAll('img[data-src]');
        
        images.forEach(img => {
            // 添加懒加载类
            img.classList.add('lazyload');
            
            // 设置占位背景
            if (!img.style.backgroundColor) {
                img.style.backgroundColor = config.placeholderColor;
            }
            
            // 开始观察
            imageObserver.observe(img);
        });
    }
    
    // DOM加载完成后初始化
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLazyLoad);
    } else {
        initLazyLoad();
    }
    
    // 暴露全局方法
    window.LazyLoad = {
        refresh: initLazyLoad,
        load: loadImage
    };
    
})();
