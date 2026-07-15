<?php
namespace app\model;

use core\Model;

/**
 * 咨询投诉模型
 */
class Consult extends Model {
    
    protected $table = 'consult';
    
    /**
     * 获取待处理数量
     */
    public function getPendingCount() {
        return $this->where('status', 0)->count();
    }
    
    /**
     * 回复咨询
     */
    public function reply($id, $content, $adminId) {
        return $this->where('id', $id)->update([
            'reply_content' => $content,
            'reply_time' => date('Y-m-d H:i:s'),
            'reply_user_id' => $adminId,
            'status' => 2
        ]);
    }
}
