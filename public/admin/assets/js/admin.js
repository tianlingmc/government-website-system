/**
 * 后台管理主脚本
 */

$(function() {
    'use strict';
    
    // 初始化AdminLTE
    $('body').layout();
    
    // 初始化工具提示
    $('[data-toggle="tooltip"]').tooltip();
    
    // 初始化弹出框
    $('[data-toggle="popover"]').popover();
    
    // 初始化DataTable（如果存在）
    initDataTables();
    
    // 初始化表单验证
    initFormValidation();
    
    // 初始化文件上传
    initFileUpload();
    
    // 初始化富文本编辑器
    initEditor();
    
    // 初始化确认删除
    initConfirmDelete();
    
    // 初始化批量操作
    initBatchActions();
    
    // 初始化侧边栏菜单状态
    initSidebarMenu();
});

// DataTable初始化
function initDataTables() {
    if ($.fn.DataTable) {
        $('.data-table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Chinese.json'
            },
            pageLength: 15,
            lengthMenu: [10, 15, 25, 50, 100],
            responsive: true
        });
    }
}

// 表单验证
function initFormValidation() {
    $('form[data-validate]').on('submit', function(e) {
        let isValid = true;
        const form = $(this);
        
        // 验证必填字段
        form.find('[required]').each(function() {
            const field = $(this);
            if (!field.val().trim()) {
                isValid = false;
                field.addClass('is-invalid');
                
                // 显示错误提示
                let feedback = field.siblings('.invalid-feedback');
                if (feedback.length === 0) {
                    feedback = $('<div class="invalid-feedback">此字段不能为空</div>');
                    field.after(feedback);
                }
            } else {
                field.removeClass('is-invalid').addClass('is-valid');
            }
        });
        
        // 验证邮箱格式
        form.find('input[type="email"]').each(function() {
            const field = $(this);
            const email = field.val();
            if (email && !validateEmail(email)) {
                isValid = false;
                field.addClass('is-invalid');
            }
        });
        
        // 验证手机号
        form.find('input[data-type="phone"]').each(function() {
            const field = $(this);
            const phone = field.val();
            if (phone && !validatePhone(phone)) {
                isValid = false;
                field.addClass('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            showToast('请检查表单填写是否正确', 'warning');
        }
    });
    
    // 输入时移除错误状态
    $('input, textarea, select').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
}

// 文件上传
function initFileUpload() {
    const uploadArea = $('.upload-area');
    
    if (uploadArea.length) {
        // 拖拽上传
        uploadArea.on('dragover', function(e) {
            e.preventDefault();
            $(this).addClass('dragover');
        });
        
        uploadArea.on('dragleave', function(e) {
            e.preventDefault();
            $(this).removeClass('dragover');
        });
        
        uploadArea.on('drop', function(e) {
            e.preventDefault();
            $(this).removeClass('dragover');
            
            const files = e.originalEvent.dataTransfer.files;
            handleFiles(files);
        });
        
        // 点击上传
        uploadArea.on('click', function() {
            $(this).find('input[type="file"]').trigger('click');
        });
        
        uploadArea.find('input[type="file"]').on('change', function() {
            handleFiles(this.files);
        });
    }
}

// 处理文件
function handleFiles(files) {
    if (files.length === 0) return;
    
    const formData = new FormData();
    for (let i = 0; i < files.length; i++) {
        formData.append('files[]', files[i]);
    }
    
    // 显示上传进度
    showToast('正在上传文件...', 'info');
    
    $.ajax({
        url: '/admin/media/upload',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.code === 200) {
                showToast('文件上传成功', 'success');
                // 刷新页面或更新文件列表
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(response.message || '上传失败', 'error');
            }
        },
        error: function() {
            showToast('上传失败，请重试', 'error');
        }
    });
}

// 富文本编辑器
function initEditor() {
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '.editor',
            height: 400,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount'
            ],
            toolbar: 'undo redo | formatselect | bold italic backcolor | \
                     alignleft aligncenter alignright alignjustify | \
                     bullist numlist outdent indent | removeformat | help',
            language: 'zh_CN',
            images_upload_url: '/admin/media/upload',
            automatic_uploads: true
        });
    }
}

// 确认删除
function initConfirmDelete() {
    $(document).on('click', '[data-action="delete"]', function(e) {
        e.preventDefault();
        
        const url = $(this).attr('href');
        const title = $(this).data('title') || '确定要删除吗？';
        
        if (confirm(title)) {
            $.ajax({
                url: url,
                type: 'POST',
                data: { _method: 'DELETE' },
                success: function(response) {
                    if (response.code === 200) {
                        showToast('删除成功', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast(response.message || '删除失败', 'error');
                    }
                },
                error: function() {
                    showToast('删除失败，请重试', 'error');
                }
            });
        }
    });
}

// 批量操作
function initBatchActions() {
    // 全选
    $('#select-all').on('change', function() {
        $('.select-item').prop('checked', $(this).prop('checked'));
        updateBatchButton();
    });
    
    // 单选
    $(document).on('change', '.select-item', function() {
        updateBatchButton();
    });
    
    // 批量删除
    $('#batch-delete').on('click', function() {
        const ids = getSelectedIds();
        if (ids.length === 0) {
            showToast('请先选择要删除的项目', 'warning');
            return;
        }
        
        if (confirm(`确定要删除选中的 ${ids.length} 个项目吗？`)) {
            batchDelete(ids);
        }
    });
}

// 获取选中的ID
function getSelectedIds() {
    const ids = [];
    $('.select-item:checked').each(function() {
        ids.push($(this).val());
    });
    return ids;
}

// 更新批量操作按钮状态
function updateBatchButton() {
    const count = $('.select-item:checked').length;
    $('#batch-actions button').prop('disabled', count === 0);
    $('#selected-count').text(count);
}

// 批量删除
function batchDelete(ids) {
    $.ajax({
        url: '/admin/batch/delete',
        type: 'POST',
        data: { ids: ids },
        success: function(response) {
            if (response.code === 200) {
                showToast('批量删除成功', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(response.message || '删除失败', 'error');
            }
        },
        error: function() {
            showToast('删除失败，请重试', 'error');
        }
    });
}

// 初始化侧边栏菜单
function initSidebarMenu() {
    // 展开当前菜单
    const currentPath = window.location.pathname;
    $('.nav-sidebar .nav-link').each(function() {
        const href = $(this).attr('href');
        if (href && currentPath.indexOf(href) !== -1 && href !== '/admin') {
            $(this).addClass('active');
            $(this).parents('.nav-treeview').prev('.nav-link').addClass('active');
        }
    });
}

// 显示提示
function showToast(message, type = 'info') {
    const toast = $(`
        <div class="alert alert-${type} alert-dismissible fade show position-fixed" 
             style="top: 20px; right: 20px; z-index: 9999; min-width: 200px;">
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `);
    
    $('body').append(toast);
    
    setTimeout(() => {
        toast.fadeOut(() => toast.remove());
    }, 3000);
}

// 验证邮箱
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// 验证手机号
function validatePhone(phone) {
    const re = /^1[3-9]\d{9}$/;
    return re.test(phone);
}

// AJAX全局设置
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// AJAX错误处理
$(document).ajaxError(function(event, xhr, settings, error) {
    if (xhr.status === 401) {
        showToast('登录已过期，请重新登录', 'warning');
        setTimeout(() => {
            window.location.href = '/admin/login';
        }, 1500);
    } else if (xhr.status === 403) {
        showToast('没有权限执行此操作', 'error');
    } else if (xhr.status === 500) {
        showToast('服务器错误，请稍后重试', 'error');
    }
});
