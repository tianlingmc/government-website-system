<?php
namespace core;

/**
 * 基础模型类
 */
abstract class Model {
    
    protected $app;
    protected $db;
    protected $table;
    protected $prefix;
    protected $primaryKey = 'id';
    protected $fields = [];
    protected $where = [];
    protected $order = '';
    protected $limit = '';
    protected $join = [];
    
    public function __construct($app = null) {
        $this->app = $app ?: App::getInstance();
        $this->db = $this->app->getDb();
        $this->prefix = $this->app->getConfig('database.prefix', 'gov_');
    }
    
    /**
     * 获取表名
     */
    protected function getTable() {
        return $this->prefix . $this->table;
    }
    
    /**
     * 设置查询条件
     */
    public function where($field, $operator = null, $value = null) {
        if (is_array($field)) {
            foreach ($field as $k => $v) {
                $this->where[] = [$k, '=', $v];
            }
        } elseif (func_num_args() === 2) {
            $this->where[] = [$field, '=', $operator];
        } else {
            $this->where[] = [$field, $operator, $value];
        }
        return $this;
    }
    
    /**
     * 设置排序
     */
    public function order($field, $direction = 'ASC') {
        $this->order = "ORDER BY {$field} {$direction}";
        return $this;
    }
    
    /**
     * 设置限制
     */
    public function limit($offset, $limit = null) {
        if ($limit === null) {
            $this->limit = "LIMIT {$offset}";
        } else {
            $this->limit = "LIMIT {$offset}, {$limit}";
        }
        return $this;
    }
    
    /**
     * 构建WHERE语句
     */
    protected function buildWhere() {
        if (empty($this->where)) {
            return '';
        }
        
        $conditions = [];
        foreach ($this->where as $w) {
            $conditions[] = "{$w[0]} {$w[1]} ?";
        }
        
        return 'WHERE ' . implode(' AND ', $conditions);
    }
    
    /**
     * 获取WHERE参数
     */
    protected function getWhereParams() {
        $params = [];
        foreach ($this->where as $w) {
            // 两参数情况: [field, value]，value在索引1
            // 三参数情况: [field, operator, value]，value在索引2
            $params[] = $w[2] ?? $w[1];
        }
        return $params;
    }
    
    /**
     * 查询单条记录
     */
    public function find($id = null) {
        if ($id !== null) {
            $this->where($this->primaryKey, $id);
        }
        
        $sql = "SELECT * FROM {$this->getTable()} {$this->buildWhere()} {$this->order} LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($this->getWhereParams());
        
        $this->reset();
        return $stmt->fetch();
    }
    
    /**
     * 查询多条记录
     */
    public function select() {
        $sql = "SELECT * FROM {$this->getTable()} {$this->buildWhere()} {$this->order} {$this->limit}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($this->getWhereParams());
        
        $this->reset();
        return $stmt->fetchAll();
    }
    
    /**
     * 查询数量
     */
    public function count() {
        $sql = "SELECT COUNT(*) FROM {$this->getTable()} {$this->buildWhere()}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($this->getWhereParams());
        
        $this->reset();
        return $stmt->fetchColumn();
    }
    
    /**
     * 插入数据
     */
    public function insert($data) {
        $fields = array_keys($data);
        $values = array_values($data);
        $placeholders = array_fill(0, count($fields), '?');
        
        $sql = "INSERT INTO {$this->getTable()} (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($values);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * 更新数据
     */
    public function update($data) {
        $fields = [];
        $values = [];
        foreach ($data as $k => $v) {
            $fields[] = "{$k} = ?";
            $values[] = $v;
        }
        
        $sql = "UPDATE {$this->getTable()} SET " . implode(', ', $fields) . " {$this->buildWhere()}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_merge($values, $this->getWhereParams()));
        
        $this->reset();
        return $stmt->rowCount();
    }
    
    /**
     * 删除数据
     */
    public function delete() {
        $sql = "DELETE FROM {$this->getTable()} {$this->buildWhere()}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($this->getWhereParams());
        
        $this->reset();
        return $stmt->rowCount();
    }
    
    /**
     * 分页查询
     */
    public function paginate($page = 1, $pageSize = 15) {
        $page = max(1, intval($page));
        $pageSize = max(1, intval($pageSize));
        $offset = ($page - 1) * $pageSize;
        
        $total = $this->count();
        $this->reset();
        
        $this->limit($offset, $pageSize);
        $data = $this->select();
        
        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'page_size' => $pageSize,
            'total_page' => ceil($total / $pageSize)
        ];
    }
    
    /**
     * 设置分页（链式调用）
     */
    public function page($page = 1, $pageSize = 15) {
        $page = max(1, intval($page));
        $pageSize = max(1, intval($pageSize));
        $offset = ($page - 1) * $pageSize;
        
        $this->limit($offset, $pageSize);
        return $this;
    }
    
    /**
     * 原生查询
     */
    public function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    /**
     * 重置查询条件
     */
    protected function reset() {
        $this->where = [];
        $this->order = '';
        $this->limit = '';
        $this->join = [];
    }
}
