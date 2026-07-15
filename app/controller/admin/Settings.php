<?php
namespace app\controller\admin;

use core\Controller;
use app\model\Settings as SettingsModel;

/**
 * 系统设置控制器
 */
class Settings extends Controller {
    
    /**
     * 基本设置
     */
    public function index() {
        $settingsModel = new SettingsModel();
        
        if ($this->isMethod('POST')) {
            $settings = [
                'site_title' => $this->post('site_title'),
                'site_subtitle' => $this->post('site_subtitle'),
                'site_url' => $this->post('site_url'),
                'site_logo' => $this->post('site_logo'),
                'site_favicon' => $this->post('site_favicon'),
                'seo_keywords' => $this->post('seo_keywords'),
                'seo_description' => $this->post('seo_description'),
                'icp_number' => $this->post('icp_number'),
                'police_number' => $this->post('police_number'),
                'copyright' => $this->post('copyright'),
                'contact_address' => $this->post('contact_address'),
                'contact_phone' => $this->post('contact_phone'),
                'contact_email' => $this->post('contact_email'),
                'contact_work_time' => $this->post('contact_work_time')
            ];
            
            foreach ($settings as $key => $value) {
                $settingsModel->set($key, $value);
            }
            
            $this->successRedirect('保存成功', '/admin/settings');
        }
        
        // 获取所有设置
        $config = $settingsModel->getAll();
        $this->assign('config', $config);
        $this->fetch('settings/index');
    }
    
    /**
     * 清除缓存
     */
    public function clearCache() {
        $cachePath = RUNTIME_PATH . 'cache/';
        $this->deleteDir($cachePath);
        
        return $this->success('缓存清除成功');
    }
    
    /**
     * 系统信息
     */
    public function system() {
        $info = [
            'php_version' => PHP_VERSION,
            'os' => PHP_OS,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'max_execution_time' => ini_get('max_execution_time'),
            'memory_limit' => ini_get('memory_limit'),
            'database_version' => $this->getDbVersion(),
            'install_time' => date('Y-m-d H:i:s', filemtime(ROOT_PATH . 'install.lock') ?: time())
        ];
        
        $this->assign('info', $info);
        $this->fetch('settings/system');
    }
    
    /**
     * 获取数据库版本
     */
    private function getDbVersion() {
        try {
            $db = $this->app->getDb();
            $result = $db->query("SELECT VERSION() as version");
            return $result->fetch()['version'] ?? 'Unknown';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }
    
    /**
     * 递归删除目录
     */
    private function deleteDir($dir) {
        if (!is_dir($dir)) return;
        
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->deleteDir($path) : unlink($path);
        }
    }
}
