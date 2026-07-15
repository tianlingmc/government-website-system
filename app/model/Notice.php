<?php
namespace app\model;

use core\Model;

/**
 * 公告模型
 */
class Notice extends Model {
    
    protected $table = 'notice';
    
    /**
     * 获取最新公告
     */
    public function getLatest($limit = 5) {
        return $this->where('status', 1)
                    ->order('is_top', 'DESC')
                    ->order('publish_time', 'DESC')
                    ->limit($limit)
                    ->select();
    }
    
    /**
     * 获取置顶公告
     */
    public function getTopNotices($limit = 3) {
        return $this->where('status', 1)
                    ->where('is_top', 1)
                    ->order('publish_time', 'DESC')
                    ->limit($limit)
                    ->select();
    }
    
    /**
     * 增加浏览量
     */
    public function increaseViews($id) {
        $sql = "UPDATE {$this->getTable()} SET views = views + 1 WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    /**
     * 获取公告列表（分页）
     */
    public function getList($page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        return $this->where('status', 1)
                    ->order('is_top', 'DESC')
                    ->order('publish_time', 'DESC')
                    ->limit($offset, $limit)
                    ->select();
    }
    
    /**
     * 获取总数
     */
    public function getTotal() {
        return $this->where('status', 1)->count();
    }
    
    /**
     * 根据ID查找
     */
    public function findById($id) {
        return $this->where('id', $id)->find();
    }
    
    /**
     * 搜索公告
     */
    public function search($keyword, $page = 1, $pageSize = 15) {
        $sql = "SELECT * FROM {$this->getTable()} 
                WHERE status = 1 
                AND (title LIKE ? OR content LIKE ? OR summary LIKE ?)
                ORDER BY is_top DESC, publish_time DESC LIMIT ?, ?";
        
        $likeKeyword = "%{$keyword}%";
        $offset = ($page - 1) * $pageSize;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$likeKeyword, $likeKeyword, $likeKeyword, $offset, $pageSize]);
        
        // 获取总数
        $countSql = "SELECT COUNT(*) FROM {$this->getTable()} 
                     WHERE status = 1 
                     AND (title LIKE ? OR content LIKE ? OR summary LIKE ?)";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute([$likeKeyword, $likeKeyword, $likeKeyword]);
        $total = $countStmt->fetchColumn();
        
        return [
            'data' => $stmt->fetchAll(),
            'total' => $total,
            'page' => $page,
            'page_size' => $pageSize,
            'total_page' => ceil($total / $pageSize)
        ];
    }
}
