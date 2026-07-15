<?php
namespace core;

/**
 * 数据库查询构建器
 */
class Db {
    
    private $pdo;
    private $prefix;
    private $table;
    private $where = [];
    private $order = '';
    private $limit = '';
    private $fields = '*';
    
    public function __construct($pdo, $prefix = '') {
        $this->pdo = $pdo;
        $this->prefix = $prefix;
    }
    
    /**
     * 设置表名
     */
    public function table($table) {
        $this->table = $this->prefix . $table;
        $this->where = [];
        $this->order = '';
        $this->limit = '';
        $this->fields = '*';
        return $this;
    }
    
    /**
     * 设置查询字段
     */
    public function field($fields) {
        $this->fields = $fields;
        return $this;
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
        $field = $this->escapeField($field);
        $this->order = "ORDER BY {$field} {$direction}";
        return $this;
    }
    
    /**
     * 设置分页
     */
    public function page($page, $pageSize = 15) {
        $page = max(1, intval($page));
        $pageSize = max(1, intval($pageSize));
        $offset = ($page - 1) * $pageSize;
        $this->limit = "LIMIT {$offset}, {$pageSize}";
        return $this;
    }
    
    /**
     * 设置限制
     */
    public function limit($offset, $count = null) {
        if ($count === null) {
            $this->limit = "LIMIT {$offset}";
        } else {
            $this->limit = "LIMIT {$offset}, {$count}";
        }
        return $this;
    }
    
    /**
     * 查询单条记录
     */
    public function find() {
        $this->limit = "LIMIT 1";
        $sql = $this->buildSelectSql();
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->getWhereParams());
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }
    
    /**
     * 查询多条记录
     */
    public function select() {
        $sql = $this->buildSelectSql();
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->getWhereParams());
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * 获取记录数
     */
    public function count() {
        $this->fields = 'COUNT(*)';
        $sql = $this->buildSelectSql();
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->getWhereParams());
        return (int) $stmt->fetchColumn();
    }
    
    /**
     * 插入数据
     */
    public function insert($data) {
        $fields = array_keys($data);
        $values = array_values($data);
        $placeholders = array_fill(0, count($fields), '?');
        
        // 转义字段名
        $escapedFields = array_map([$this, 'escapeField'], $fields);
        
        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $this->table,
            implode(', ', $escapedFields),
            implode(', ', $placeholders)
        );
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values);
        return $this->pdo->lastInsertId();
    }
    
    /**
     * 更新数据
     */
    public function update($data) {
        $sets = [];
        $values = [];
        
        foreach ($data as $field => $value) {
            $field = $this->escapeField($field);
            $sets[] = "{$field} = ?";
            $values[] = $value;
        }
        
        $whereSql = $this->buildWhereSql();
        $sql = sprintf(
            "UPDATE %s SET %s %s",
            $this->table,
            implode(', ', $sets),
            $whereSql
        );
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_merge($values, $this->getWhereParams()));
        return $stmt->rowCount();
    }
    
    /**
     * 删除数据
     */
    public function delete() {
        $whereSql = $this->buildWhereSql();
        $sql = sprintf("DELETE FROM %s %s", $this->table, $whereSql);
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->getWhereParams());
        return $stmt->rowCount();
    }
    
    /**
     * 执行原始SQL
     */
    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    /**
     * 构建查询SQL
     */
    private function buildSelectSql() {
        $whereSql = $this->buildWhereSql();
        return sprintf(
            "SELECT %s FROM %s %s %s %s",
            $this->fields,
            $this->table,
            $whereSql,
            $this->order,
            $this->limit
        );
    }
    
    /**
     * 构建WHERE子句
     */
    private function buildWhereSql() {
        if (empty($this->where)) {
            return '';
        }
        
        $conditions = [];
        foreach ($this->where as $w) {
            $field = $this->escapeField($w[0]);
            $conditions[] = "{$field} {$w[1]} ?";
        }
        
        return 'WHERE ' . implode(' AND ', $conditions);
    }
    
    /**
     * 转义字段名
     */
    private function escapeField($field) {
        // 处理带表名的字段，如 "table.field"
        if (strpos($field, '.') !== false) {
            $parts = explode('.', $field);
            return implode('.', array_map(function($p) {
                return '`' . trim($p, '`') . '`';
            }, $parts));
        }
        return '`' . trim($field, '`') . '`';
    }
    
    /**
     * 获取WHERE参数
     */
    private function getWhereParams() {
        $params = [];
        foreach ($this->where as $w) {
            $params[] = $w[2] ?? $w[1];
        }
        return $params;
    }
    
    /**
     * 转义字符串
     */
    public function quote($value) {
        return $this->pdo->quote($value);
    }
}
