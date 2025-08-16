<?php
/**
 * EasyForm Performance Monitor
 * Track and optimize system performance
 */

namespace EasyForm;

class Performance {
    private static $instance = null;
    private $startTime;
    private $startMemory;
    private $marks = [];
    private $queries = [];
    private $slowQueries = [];
    private $threshold = 1.0; // Slow query threshold in seconds
    
    private function __construct() {
        $this->startTime = microtime(true);
        $this->startMemory = memory_get_usage(true);
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Mark a performance checkpoint
     */
    public function mark($name) {
        $this->marks[$name] = [
            'time' => microtime(true),
            'memory' => memory_get_usage(true),
            'elapsed' => microtime(true) - $this->startTime
        ];
        return $this;
    }
    
    /**
     * Measure execution time of a callback
     */
    public function measure($name, callable $callback) {
        $start = microtime(true);
        $startMem = memory_get_usage(true);
        
        $result = $callback();
        
        $this->marks[$name] = [
            'time' => microtime(true) - $start,
            'memory' => memory_get_usage(true) - $startMem,
            'elapsed' => microtime(true) - $this->startTime
        ];
        
        return $result;
    }
    
    /**
     * Log database query
     */
    public function logQuery($sql, $time, $params = []) {
        $query = [
            'sql' => $sql,
            'time' => $time,
            'params' => $params,
            'timestamp' => microtime(true)
        ];
        
        $this->queries[] = $query;
        
        if ($time > $this->threshold) {
            $this->slowQueries[] = $query;
        }
        
        return $this;
    }
    
    /**
     * Get performance report
     */
    public function getReport() {
        $totalTime = microtime(true) - $this->startTime;
        $totalMemory = memory_get_usage(true) - $this->startMemory;
        
        return [
            'execution_time' => round($totalTime * 1000, 2) . ' ms',
            'memory_usage' => $this->formatBytes($totalMemory),
            'memory_peak' => $this->formatBytes(memory_get_peak_usage(true)),
            'marks' => $this->formatMarks(),
            'database' => [
                'total_queries' => count($this->queries),
                'slow_queries' => count($this->slowQueries),
                'total_time' => round(array_sum(array_column($this->queries, 'time')) * 1000, 2) . ' ms'
            ],
            'system' => [
                'php_version' => PHP_VERSION,
                'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                'load_average' => function_exists('sys_getloadavg') ? sys_getloadavg() : 'N/A'
            ]
        ];
    }
    
    /**
     * Get recommendations for optimization
     */
    public function getRecommendations() {
        $recommendations = [];
        
        // Check execution time
        $totalTime = microtime(true) - $this->startTime;
        if ($totalTime > 3.0) {
            $recommendations[] = [
                'type' => 'warning',
                'message' => 'Page load time exceeds 3 seconds',
                'suggestion' => 'Consider implementing caching and optimizing database queries'
            ];
        }
        
        // Check memory usage
        $memoryUsage = memory_get_usage(true);
        if ($memoryUsage > 50 * 1024 * 1024) { // 50MB
            $recommendations[] = [
                'type' => 'warning',
                'message' => 'High memory usage detected',
                'suggestion' => 'Optimize data processing and implement pagination'
            ];
        }
        
        // Check slow queries
        if (count($this->slowQueries) > 0) {
            $recommendations[] = [
                'type' => 'critical',
                'message' => count($this->slowQueries) . ' slow queries detected',
                'suggestion' => 'Add database indexes and optimize query structure'
            ];
        }
        
        // Check query count
        if (count($this->queries) > 50) {
            $recommendations[] = [
                'type' => 'warning',
                'message' => 'Too many database queries (' . count($this->queries) . ')',
                'suggestion' => 'Implement query caching and reduce N+1 queries'
            ];
        }
        
        return $recommendations;
    }
    
    /**
     * Output performance toolbar
     */
    public function renderToolbar() {
        if (!$this->shouldShowToolbar()) {
            return '';
        }
        
        $report = $this->getReport();
        $recommendations = $this->getRecommendations();
        
        ob_start();
        ?>
        <div id="perf-toolbar" style="position:fixed;bottom:0;left:0;right:0;background:#222;color:#fff;font:12px monospace;z-index:99999;">
            <style>
                #perf-toolbar {padding:10px;border-top:2px solid #f39c12;}
                #perf-toolbar span {margin-right:20px;display:inline-block;}
                #perf-toolbar .label {color:#95a5a6;}
                #perf-toolbar .value {color:#3498db;font-weight:bold;}
                #perf-toolbar .warning {color:#f39c12;}
                #perf-toolbar .critical {color:#e74c3c;}
                #perf-toolbar .toggle {position:absolute;right:10px;top:10px;cursor:pointer;}
            </style>
            <span class="toggle" onclick="this.parentElement.style.display='none'">✕</span>
            <span><span class="label">Time:</span> <span class="value"><?= $report['execution_time'] ?></span></span>
            <span><span class="label">Memory:</span> <span class="value"><?= $report['memory_usage'] ?></span></span>
            <span><span class="label">Peak:</span> <span class="value"><?= $report['memory_peak'] ?></span></span>
            <span><span class="label">Queries:</span> <span class="value"><?= $report['database']['total_queries'] ?></span></span>
            <?php if ($report['database']['slow_queries'] > 0): ?>
                <span><span class="label">Slow:</span> <span class="critical"><?= $report['database']['slow_queries'] ?></span></span>
            <?php endif; ?>
            <?php if (count($recommendations) > 0): ?>
                <span class="warning">⚠ <?= count($recommendations) ?> warnings</span>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Format marks for display
     */
    private function formatMarks() {
        $formatted = [];
        foreach ($this->marks as $name => $data) {
            $formatted[$name] = [
                'time' => isset($data['time']) ? round($data['time'] * 1000, 2) . ' ms' : 'N/A',
                'memory' => isset($data['memory']) ? $this->formatBytes($data['memory']) : 'N/A',
                'elapsed' => isset($data['elapsed']) ? round($data['elapsed'] * 1000, 2) . ' ms' : 'N/A'
            ];
        }
        return $formatted;
    }
    
    /**
     * Format bytes to human readable
     */
    private function formatBytes($bytes) {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
    
    /**
     * Check if toolbar should be shown
     */
    private function shouldShowToolbar() {
        // Only show in development environment
        return isset($_GET['debug']) || 
               (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] === 'localhost');
    }
    
    /**
     * Export performance data as JSON
     */
    public function export() {
        return json_encode($this->getReport(), JSON_PRETTY_PRINT);
    }
}