<?php
/**
 * Simple Autoloader für EasyForm
 */

spl_autoload_register(function ($class) {
    // Namespace prefix
    $prefix = 'EasyForm\\';
    
    // Base directory for namespace prefix
    $base_dir = __DIR__ . '/';
    
    // Does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // No, move to next registered autoloader
        return;
    }
    
    // Get relative class name
    $relative_class = substr($class, $len);
    
    // Replace namespace separators with directory separators
    // Replace underscores with directory separators in the class name
    // Append with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    // If file exists, require it
    if (file_exists($file)) {
        require $file;
    } else {
        // Try in src directory
        $file = $base_dir . 'src/' . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    }
});