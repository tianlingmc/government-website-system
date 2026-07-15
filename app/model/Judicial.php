<?php
namespace app\model;

use core\Model;

/**
 * 裁判文书模型
 */
class Judicial extends Model {
    
    protected $table = 'judicial';
    
    /**
     * 搜索文书
     */
    public function search($keyword, $page = 1, $pageSize = 15) {
        $this->where('is_public', 1)
             ->where('check_status', 1)
             ->where('status', 1);
        
        if (!empty($keyword)) {
            $sql = "SELECT * FROM {$this->getTable()} 
                    WHERE is_public = 1 AND check_status = 1 AND status = 1 
                    AND (case_name LIKE ? OR case_no LIKE ? OR court LIKE ? OR content LIKE ?)
                    ORDER BY judge_date DESC LIMIT ?, ?";
            
            $likeKeyword = "%{$keyword}%";
            $offset = ($page - 1) * $pageSize;
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$likeKeyword, $likeKeyword, $likeKeyword, $likeKeyword, $offset, $pageSize]);
            
            // 获取总数
            $countSql = "SELECT COUNT(*) FROM {$this->getTable()} 
                         WHERE is_public = 1 AND check_status = 1 AND status = 1 
                         AND (case_name LIKE ? OR case_no LIKE ? OR court LIKE ? OR content LIKE ?)";
            $countStmt = $this->db->prepare($countSql);
            $countStmt->execute([$likeKeyword, $likeKeyword, $likeKeyword, $likeKeyword]);
            $total = $countStmt->fetchColumn();
            
            return [
                'data' => $stmt->fetchAll(),
                'total' => $total,
                'page' => $page,
                'page_size' => $pageSize,
                'total_page' => ceil($total / $pageSize)
            ];
        }
        
        return $this->paginate($page, $pageSize);
    }
    
    /**
     * 获取待审核文书
     */
    public function getPendingCheck($limit = 10) {
        return $this->where('check_status', 0)
                    ->order('create_time', 'DESC')
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
}
