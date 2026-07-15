<?php
namespace app\model;

use core\Model;

/**
 * 语言配置模型
 */
class Language extends Model {
    
    protected $table = 'language';
    
    /**
     * 获取分组语言
     */
    public function getGroup($group, $module = 'common') {
        $cacheKey = "lang_{$group}_{$module}";
        
        // 尝试从缓存获取
        $cache = $this->app->getCache();
        if ($cache) {
            $cached = $cache->get($cacheKey);
            if ($cached) {
                return json_decode($cached, true);
            }
        }
        
        // 从数据库获取
        $languages = $this->where('lang_group', $group)
                          ->where('module', $module)
                          ->select();
        
        $result = [];
        foreach ($languages as $lang) {
            $result[$lang['lang_key']] = $lang['lang_value'];
        }
        
        // 写入缓存
        if ($cache) {
            $cache->setex($cacheKey, 3600, json_encode($result));
        }
        
        return $result;
    }
    
    /**
     * 获取单个语言项
     */
    public function getLang($key, $default = '') {
        $lang = $this->where('lang_key', $key)->find();
        return $lang ? $lang['lang_value'] : $default;
    }
    
    /**
     * 获取模块所有语言
     */
    public function getModuleLang($module) {
        $cacheKey = "lang_module_{$module}";
        
        $cache = $this->app->getCache();
        if ($cache) {
            $cached = $cache->get($cacheKey);
            if ($cached) {
                return json_decode($cached, true);
            }
        }
        
        $languages = $this->where('module', $module)->select();
        $result = [];
        foreach ($languages as $lang) {
            $result[$lang['lang_key']] = $lang['lang_value'];
        }
        
        if ($cache) {
            $cache->setex($cacheKey, 3600, json_encode($result));
        }
        
        return $result;
    }
    
    /**
     * 清除语言缓存
     */
    public function clearCache() {
        $cache = $this->app->getCache();
        if ($cache) {
            $keys = $cache->keys('lang_*');
            foreach ($keys as $key) {
                $cache->del($key);
            }
        }
        return true;
    }
}
