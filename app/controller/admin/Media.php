<?php
namespace app\controller\admin;

use core\Controller;

/**
 * 媒体库管理控制器
 */
class Media extends Controller {
    
    /**
     * 媒体列表
     */
    public function index() {
        $page = $this->get('page', 1);
        $limit = 20;
        $type = $this->get('type', '');
        
        $mediaPath = PUBLIC_PATH . 'media/';
        $files = [];
        
        if (is_dir($mediaPath)) {
            $iterator = new \DirectoryIterator($mediaPath);
            foreach ($iterator as $fileinfo) {
                if ($fileinfo->isFile()) {
                    $ext = strtolower($fileinfo->getExtension());
                    $fileType = $this->getFileType($ext);
                    
                    if ($type === '' || $type === $fileType) {
                        $files[] = [
                            'name' => $fileinfo->getFilename(),
                            'size' => $this->formatSize($fileinfo->getSize()),
                            'type' => $fileType,
                            'ext' => $ext,
                            'url' => '/media/' . $fileinfo->getFilename(),
                            'time' => date('Y-m-d H:i:s', $fileinfo->getMTime())
                        ];
                    }
                }
            }
        }
        
        // 按时间倒序
        usort($files, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });
        
        $total = count($files);
        $offset = ($page - 1) * $limit;
        $list = array_slice($files, $offset, $limit);
        
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('total', $total);
        $this->assign('totalPage', ceil($total / $limit));
        $this->assign('type', $type);
        $this->fetch('media/index');
    }
    
    /**
     * 上传文件
     */
    public function upload() {
        if (!$this->isMethod('POST')) {
            return $this->error('请求方式错误');
        }
        
        if (!isset($_FILES['file'])) {
            return $this->error('请选择文件');
        }
        
        $file = $_FILES['file'];
        
        // 检查上传错误
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return $this->error('上传失败：' . $this->getUploadError($file['error']));
        }
        
        // 检查文件类型
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!in_array($file['type'], $allowedTypes)) {
            return $this->error('不支持的文件类型');
        }
        
        // 检查文件大小 (最大 10MB)
        if ($file['size'] > 10 * 1024 * 1024) {
            return $this->error('文件大小不能超过 10MB');
        }
        
        // 生成文件名
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = date('Ymd') . '_' . uniqid() . '.' . $ext;
        $uploadPath = PUBLIC_PATH . 'media/' . $filename;
        
        // 移动文件
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return $this->success('上传成功', ['url' => '/media/' . $filename]);
        } else {
            return $this->error('上传失败');
        }
    }
    
    /**
     * 删除文件
     */
    public function delete() {
        $filename = $this->post('filename');
        $filepath = PUBLIC_PATH . 'media/' . basename($filename);
        
        if (file_exists($filepath) && is_file($filepath)) {
            unlink($filepath);
            return $this->success('删除成功');
        }
        
        return $this->error('文件不存在');
    }
    
    /**
     * 获取文件类型
     */
    private function getFileType($ext) {
        $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
        $videoExts = ['mp4', 'avi', 'mov', 'wmv', 'flv'];
        $audioExts = ['mp3', 'wav', 'ogg', 'wma'];
        $docExts = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'];
        
        if (in_array($ext, $imageExts)) return 'image';
        if (in_array($ext, $videoExts)) return 'video';
        if (in_array($ext, $audioExts)) return 'audio';
        if (in_array($ext, $docExts)) return 'document';
        
        return 'other';
    }
    
    /**
     * 格式化文件大小
     */
    private function formatSize($size) {
        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;
        
        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }
        
        return round($size, 2) . ' ' . $units[$unitIndex];
    }
    
    /**
     * 获取上传错误信息
     */
    private function getUploadError($code) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => '文件大小超过服务器限制',
            UPLOAD_ERR_FORM_SIZE => '文件大小超过表单限制',
            UPLOAD_ERR_PARTIAL => '文件只有部分被上传',
            UPLOAD_ERR_NO_FILE => '没有文件被上传',
            UPLOAD_ERR_NO_TMP_DIR => '找不到临时文件夹',
            UPLOAD_ERR_CANT_WRITE => '文件写入失败',
            UPLOAD_ERR_EXTENSION => '上传被扩展阻止'
        ];
        
        return $errors[$code] ?? '未知错误';
    }
}
