<?php
/**
 * EasyForm/EasyList Optimization Configuration
 * Performance and security settings
 */

return [
    // Performance Settings
    'performance' => [
        'cache_enabled' => true,
        'cache_ttl' => 3600, // 1 hour
        'minify_output' => true,
        'gzip_compression' => true,
        'lazy_loading' => true,
        'pagination_limit' => 50,
        'ajax_timeout' => 30000, // 30 seconds
        'debounce_delay' => 300, // milliseconds
        'throttle_limit' => 100, // milliseconds
    ],
    
    // Database Optimization
    'database' => [
        'connection_pooling' => true,
        'prepared_statements' => true,
        'query_cache' => true,
        'index_hints' => true,
        'batch_size' => 100,
        'connection_timeout' => 5,
        'read_timeout' => 30,
        'write_timeout' => 30,
    ],
    
    // Security Settings
    'security' => [
        'csrf_protection' => true,
        'xss_protection' => true,
        'sql_injection_protection' => true,
        'file_upload_validation' => true,
        'max_file_size' => 5242880, // 5MB
        'allowed_file_types' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx'],
        'rate_limiting' => true,
        'rate_limit_requests' => 100,
        'rate_limit_window' => 3600, // 1 hour
        'session_timeout' => 1800, // 30 minutes
        'secure_cookies' => true,
    ],
    
    // Caching Configuration
    'cache' => [
        'driver' => 'file', // file, redis, memcached
        'path' => __DIR__ . '/../cache',
        'prefix' => 'easyform_',
        'default_ttl' => 3600,
        'gc_probability' => 100, // Garbage collection probability (1/100)
    ],
    
    // Asset Optimization
    'assets' => [
        'combine_css' => true,
        'combine_js' => true,
        'minify_css' => true,
        'minify_js' => true,
        'use_cdn' => true,
        'versioning' => true,
        'lazy_load_images' => true,
        'webp_support' => true,
    ],
    
    // Error Handling
    'errors' => [
        'display_errors' => false,
        'log_errors' => true,
        'error_log' => __DIR__ . '/../logs/error.log',
        'error_reporting' => E_ALL & ~E_NOTICE & ~E_DEPRECATED,
        'exception_handler' => true,
        'shutdown_handler' => true,
    ],
    
    // Localization
    'localization' => [
        'default_locale' => 'de_DE',
        'timezone' => 'Europe/Berlin',
        'date_format' => 'd.m.Y',
        'time_format' => 'H:i',
        'number_format' => [
            'decimals' => 2,
            'decimal_separator' => ',',
            'thousands_separator' => '.'
        ],
    ],
    
    // Feature Flags
    'features' => [
        'ajax_forms' => true,
        'real_time_validation' => true,
        'auto_save' => true,
        'drag_drop' => true,
        'file_uploads' => true,
        'rich_text_editor' => true,
        'data_export' => true,
        'bulk_operations' => true,
        'conditional_logic' => true,
        'templates' => true,
    ],
    
    // API Settings
    'api' => [
        'enabled' => true,
        'version' => 'v2',
        'rate_limit' => 1000, // requests per hour
        'authentication' => true,
        'cors_enabled' => true,
        'allowed_origins' => ['*'],
        'response_format' => 'json',
        'compression' => true,
    ],
    
    // Monitoring
    'monitoring' => [
        'enabled' => true,
        'performance_tracking' => true,
        'error_tracking' => true,
        'user_tracking' => false, // GDPR compliance
        'metrics_endpoint' => '/metrics',
        'health_endpoint' => '/health',
    ],
];