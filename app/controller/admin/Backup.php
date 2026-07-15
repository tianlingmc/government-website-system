<?php
namespace app\controller\admin;

use core\Controller;

/**
 * 数据备份控制器
 */
class Backup extends Controller {
    
    private $backupPath;
    
    public function __construct() {
        parent::__construct();
        $this->backupPath = ROOT_PATH . 'runtime/backup/';
        if (!is_dir($this->backupPath)) {
            mkdir($this->backupPath, 0755, true);
        }
    }
    
    /**
     * 备份列表
     */
    public function index() {
        $page = $this->get('page', 1);
        $pageSize = $this->get('pageSize', 20);
        
        // 获取备份文件列表
        $backups = [];
        if (is_dir($this->backupPath)) {
            $files = glob($this->backupPath . '*.sql');
            foreach ($files as $file) {
                $backups[] = [
                    'filename' => basename($file),
                    'size' => filesize($file),
                    'created_at' => date('Y-m-d H:i:s', filemtime($file))
                ];
            }
        }
        
        // 按时间倒序
        usort($backups, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        $total = count($backups);
        $backups = array_slice($backups, ($page - 1) * $pageSize, $pageSize);
        
        $this->assign('backups', $backups);
        $this->assign('total', $total);
        $this->assign('page', $page);
        $this->assign('pageSize', $pageSize);
        $this->display('backup/index');
    }
    
    /**
     * 创建备份
     */
    public function create() {
        if (!$this->isMethod('POST')) {
            return $this->error('请求方式错误');
        }
        
        $type = $this->post('type', 'full');
        $tables = $this->post('tables', []);
        
        try {
            $filename = 'backup_' . date('Ymd_His') . '_' . $type . '.sql';
            $filepath = $this->backupPath . $filename;
            
            // 获取数据库配置
            $dbConfig = $this->getConfig('database');
            
            // 构建 mysqldump 命令
            $command = sprintf(
                'mysqldump -h%s -P%s -u%s -p%s %s',
                escapeshellarg($dbConfig['host']),
                escapeshellarg($dbConfig['port']),
                escapeshellarg($dbConfig['username']),
                escapeshellarg($dbConfig['password']),
                escapeshellarg($dbConfig['database'])
            );
            
            // 如果指定了表
            if ($type == 'custom' && !empty($tables)) {
                $command .= ' ' . implode(' ', array_map('escapeshellarg', $tables));
            }
            
            $command .= ' > ' . escapeshellarg($filepath);
            
            // 执行备份
            exec($command, $output, $returnCode);
            
            if ($returnCode !== 0) {
                // 使用 PHP 方式备份
                $this->backupWithPHP($filepath, $type, $tables);
            }
            
            // 记录日志
            $this->log('create', '创建数据库备份: ' . $filename);
            
            $this->successRedirect('备份创建成功', '/admin/backup');
        } catch (\Exception $e) {
            $this->errorRedirect('备份失败: ' . $e->getMessage(), '/admin/backup');
        }
    }
    
    /**
     * 使用 PHP 备份数据库
     */
    private function backupWithPHP($filepath, $type, $tables) {
        $db = $this->db();
        
        // 获取所有表
        if ($type == 'full' || empty($tables)) {
            $result = $db->query("SHOW TABLES");
            $tables = [];
            while ($row = $result->fetch(\PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }
        }
        
        $sql = "-- Artvy Gov Backup\n";
        $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $sql .= "-- Database: " . $this->getConfig('database')['database'] . "\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
        
        foreach ($tables as $table) {
            // 获取表结构
            $result = $db->query("SHOW CREATE TABLE `$table`");
            $row = $result->fetch(\PDO::FETCH_ASSOC);
            $sql .= "-- Table structure for `$table`\n";
            $sql .= "DROP TABLE IF EXISTS `$table`;\n";
            $sql .= $row['Create Table'] . ";\n\n";
            
            // 获取表数据
            $result = $db->query("SELECT * FROM `$table`");
            $rows = $result->fetchAll(\PDO::FETCH_ASSOC);
            
            if (!empty($rows)) {
                $sql .= "-- Dumping data for `$table`\n";
                foreach ($rows as $row) {
                    $columns = implode('`, `', array_keys($row));
                    $values = implode(', ', array_map(function($v) use ($db) {
                        return $v === null ? 'NULL' : $db->quote($v);
                    }, array_values($row)));
                    $sql .= "INSERT INTO `$table` (`$columns`) VALUES ($values);\n";
                }
                $sql .= "\n";
            }
        }
        
        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
        
        file_put_contents($filepath, $sql);
    }
    
    /**
     * 下载备份
     */
    public function download() {
        $filename = $this->get('file', '');
        $filepath = $this->backupPath . basename($filename);
        
        if (!file_exists($filepath)) {
            return $this->error('备份文件不存在');
        }
        
        // 记录日志
        $this->log('download', '下载备份文件: ' . $filename);
        
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    }
    
    /**
     * 删除备份
     */
    public function delete() {
        if (!$this->isMethod('POST')) {
            $this->errorRedirect('请求方式错误', '/admin/backup');
        }
        
        $filename = $this->post('file', '');
        $filepath = $this->backupPath . basename($filename);
        
        if (!file_exists($filepath)) {
            $this->errorRedirect('备份文件不存在', '/admin/backup');
        }
        
        unlink($filepath);
        
        // 记录日志
        $this->log('delete', '删除备份文件: ' . $filename);
        
        $this->successRedirect('删除成功', '/admin/backup');
    }
    
    /**
     * 获取数据库表列表
     */
    public function tables() {
        $db = $this->db();
        $result = $db->query("SHOW TABLES");
        $tables = [];
        while ($row = $result->fetch(\PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }
        return $this->success('获取成功', $tables);
    }
    
    /**
     * 恢复备份
     */
    public function restore() {
        if (!$this->isMethod('POST')) {
            return $this->error('请求方式错误');
        }
        
        $filename = $this->post('file', '');
        $filepath = $this->backupPath . basename($filename);
        
        if (!file_exists($filepath)) {
            return $this->error('备份文件不存在');
        }
        
        try {
            $dbConfig = $this->getConfig('database');
            
            // 使用 mysql 命令恢复
            $command = sprintf(
                'mysql -h%s -P%s -u%s -p%s %s < %s',
                escapeshellarg($dbConfig['host']),
                escapeshellarg($dbConfig['port']),
                escapeshellarg($dbConfig['username']),
                escapeshellarg($dbConfig['password']),
                escapeshellarg($dbConfig['database']),
                escapeshellarg($filepath)
            );
            
            exec($command, $output, $returnCode);
            
            if ($returnCode !== 0) {
                // 使用 PHP 方式恢复
                $sql = file_get_contents($filepath);
                $this->db()->exec($sql);
            }
            
            // 记录日志
            $this->log('update', '恢复数据库备份: ' . $filename);
            
            $this->successRedirect('恢复成功', '/admin/backup');
        } catch (\Exception $e) {
            $this->errorRedirect('恢复失败: ' . $e->getMessage(), '/admin/backup');
        }
    }
}