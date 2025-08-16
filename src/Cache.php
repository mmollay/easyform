<?php
/**
 * EasyForm Cache Manager
 * High-performance caching system
 */

namespace EasyForm;

class Cache {
    private static $instance = null;
    private $cache = [];
    private $config;
    private $stats = [
        'hits' => 0,
        'misses' => 0,
        'writes' => 0,
        'deletes' => 0
    ];
    
    private function __construct($config = []) {
        $this->config = array_merge([
            'enabled' => true,
            'ttl' => 3600,
            'prefix' => 'ef_',
            'driver' => 'memory',
            'path' => __DIR__ . '/../cache'
        ], $config);
        
        if (!is_dir($this->config['path'])) {
            mkdir($this->config['path'], 0755, true);
        }
    }
    
    public static function getInstance($config = []) {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }
    
    /**
     * Get cached value
     */
    public function get($key, $default = null) {
        if (!$this->config['enabled']) {
            return $default;
        }
        
        $key = $this->prefixKey($key);
        
        // Memory cache
        if (isset($this->cache[$key])) {
            $data = $this->cache[$key];
            if ($data['expires'] > time()) {
                $this->stats['hits']++;
                return $data['value'];
            }
            unset($this->cache[$key]);
        }
        
        // File cache
        if ($this->config['driver'] === 'file') {
            $file = $this->getCacheFile($key);
            if (file_exists($file)) {
                $data = unserialize(file_get_contents($file));
                if ($data['expires'] > time()) {
                    $this->cache[$key] = $data; // Store in memory
                    $this->stats['hits']++;
                    return $data['value'];
                }
                unlink($file);
            }
        }
        
        $this->stats['misses']++;
        return $default;
    }
    
    /**
     * Set cache value
     */
    public function set($key, $value, $ttl = null) {
        if (!$this->config['enabled']) {
            return false;
        }
        
        $key = $this->prefixKey($key);
        $ttl = $ttl ?? $this->config['ttl'];
        
        $data = [
            'value' => $value,
            'expires' => time() + $ttl,
            'created' => time()
        ];
        
        // Memory cache
        $this->cache[$key] = $data;
        
        // File cache
        if ($this->config['driver'] === 'file') {
            $file = $this->getCacheFile($key);
            file_put_contents($file, serialize($data), LOCK_EX);
        }
        
        $this->stats['writes']++;
        return true;
    }
    
    /**
     * Delete cached value
     */
    public function delete($key) {
        $key = $this->prefixKey($key);
        
        // Memory cache
        unset($this->cache[$key]);
        
        // File cache
        if ($this->config['driver'] === 'file') {
            $file = $this->getCacheFile($key);
            if (file_exists($file)) {
                unlink($file);
            }
        }
        
        $this->stats['deletes']++;
        return true;
    }
    
    /**
     * Clear all cache
     */
    public function clear() {
        $this->cache = [];
        
        if ($this->config['driver'] === 'file') {
            $files = glob($this->config['path'] . '/' . $this->config['prefix'] . '*.cache');
            foreach ($files as $file) {
                unlink($file);
            }
        }
        
        return true;
    }
    
    /**
     * Remember value with callback
     */
    public function remember($key, $ttl, callable $callback) {
        $value = $this->get($key);
        
        if ($value === null) {
            $value = $callback();
            $this->set($key, $value, $ttl);
        }
        
        return $value;
    }
    
    /**
     * Get cache statistics
     */
    public function getStats() {
        $this->stats['memory_usage'] = memory_get_usage(true);
        $this->stats['memory_peak'] = memory_get_peak_usage(true);
        $this->stats['hit_rate'] = $this->stats['hits'] + $this->stats['misses'] > 0
            ? round($this->stats['hits'] / ($this->stats['hits'] + $this->stats['misses']) * 100, 2)
            : 0;
        
        return $this->stats;
    }
    
    /**
     * Garbage collection
     */
    public function gc() {
        // Memory cache
        foreach ($this->cache as $key => $data) {
            if ($data['expires'] <= time()) {
                unset($this->cache[$key]);
            }
        }
        
        // File cache
        if ($this->config['driver'] === 'file') {
            $files = glob($this->config['path'] . '/' . $this->config['prefix'] . '*.cache');
            foreach ($files as $file) {
                $data = unserialize(file_get_contents($file));
                if ($data['expires'] <= time()) {
                    unlink($file);
                }
            }
        }
        
        return true;
    }
    
    /**
     * Prefix key with namespace
     */
    private function prefixKey($key) {
        return $this->config['prefix'] . md5($key);
    }
    
    /**
     * Get cache file path
     */
    private function getCacheFile($key) {
        return $this->config['path'] . '/' . $key . '.cache';
    }
    
    /**
     * Destructor - Run garbage collection
     */
    public function __destruct() {
        // Run GC with probability
        if (rand(1, 100) <= 1) { // 1% chance
            $this->gc();
        }
    }
}