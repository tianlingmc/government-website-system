<?php
namespace app\model;

use core\Model;

/**
 * 网站设置模型
 */
class Settings extends Model {
    
    protected $table = 'settings';
    
    /**
     * 获取所有设置
     */
    public function getAll() {
        return $this->getAllSettings();
    }
    
    /**
     * 获取所有设置
     */
    public function getAllSettings() {
        $cacheKey = 'settings_all';
        
        // 尝试从缓存获取
        $cache = $this->app->getCache();
        if ($cache) {
            $cached = $cache->get($cacheKey);
            if ($cached) {
                return json_decode($cached, true);
            }
        }
        
        // 从数据库获取
        $settings = $this->select();
        $result = [];
        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = $setting['setting_value'];
        }
        
        // 写入缓存
        if ($cache) {
            $cache->setex($cacheKey, 3600, json_encode($result));
        }
        
        return $result;
    }
    
    /**
     * 获取单个设置
     */
    public function get($key, $default = null) {
        return $this->getSetting($key, $default);
    }
    
    /**
     * 获取单个设置
     */
    public function getSetting($key, $default = null) {
        $settings = $this->getAllSettings();
        return $settings[$key] ?? $default;
    }
    
    /**
     * 保存设置
     */
    public function set($key, $value) {
        return $this->saveSetting($key, $value);
    }
    
    /**
     * 保存设置
     */
    public function saveSetting($key, $value) {
        $exists = $this->where('setting_key', $key)->find();
        
        if ($exists) {
            $this->where('setting_key', $key)->update(['setting_value' => $value]);
        } else {
            $this->insert([
                'setting_key' => $key,
                'setting_value' => $value
            ]);
        }
        
        // 清除缓存
        $cache = $this->app->getCache();
        if ($cache) {
            $cache->del('settings_all');
        }
        
        return true;
    }
    
    /**
     * 批量保存设置
     */
    public function saveSettings($data) {
        foreach ($data as $key => $value) {
            $this->saveSetting($key, $value);
        }
        return true;
    }
}
