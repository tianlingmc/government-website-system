<?php
namespace app\model;

use core\Model;

/**
 * 政策法规模型
 */
class Policy extends Model {
    
    protected $table = 'policy';
    
    /**
     * 获取最新政策
     */
    public function getLatest($limit = 5) {
        return $this->where('status', 1)
                    ->order('publish_date', 'DESC')
                    ->limit($limit)
                    ->select();
    }
    
    /**
     * 按分类获取政策
     */
    public function getByCategory($categoryId, $limit = 10) {
        return $this->where('status', 1)
                    ->where('category_id', $categoryId)
                    ->order('publish_date', 'DESC')
                    ->limit($limit)
                    ->select();
    }
    
    /**
     * 搜索政策
     */
    public function search($keyword, $page = 1, $pageSize = 15) {
        $sql = "SELECT * FROM {$this->getTable()} 
                WHERE status = 1 
                AND (title LIKE ? OR content LIKE ? OR publish_org LIKE ?)
                ORDER BY publish_date DESC LIMIT ?, ?";
        
        $likeKeyword = "%{$keyword}%";
        $offset = ($page - 1) * $pageSize;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$likeKeyword, $likeKeyword, $likeKeyword, $offset, $pageSize]);
        
        // 获取总数
        $countSql = "SELECT COUNT(*) FROM {$this->getTable()} 
                     WHERE status = 1 
                     AND (title LIKE ? OR content LIKE ? OR publish_org LIKE ?)";
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
