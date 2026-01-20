<?php
/**
 * Router script for PHP built-in server
 * Properly serves static files before routing to index.php
 */

// Get the requested URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = urldecode($uri);

// Remove /easy_form/ prefix if present
$uri = preg_replace('#^/easy_form/#', '/', $uri);

// Build the file path
$filepath = __DIR__ . $uri;

// If it's a directory, look for index.php or index.html
if (is_dir($filepath)) {
    $filepath = rtrim($filepath, '/');
    if (file_exists($filepath . '/index.php')) {
        $filepath .= '/index.php';
    } elseif (file_exists($filepath . '/index.html')) {
        $filepath .= '/index.html';
    } else {
        $filepath .= '/index.php'; // fallback
    }
}

// If the file exists and is not this router script, serve it
if (file_exists($filepath) && $filepath !== __FILE__) {
    // Determine MIME type
    $ext = pathinfo($filepath, PATHINFO_EXTENSION);
    $mimeTypes = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'eot' => 'application/vnd.ms-fontobject',
        'html' => 'text/html',
        'htm' => 'text/html',
        'xml' => 'text/xml',
    ];

    $mimeType = $mimeTypes[$ext] ?? 'application/octet-stream';

    // For PHP files, include them directly
    if ($ext === 'php') {
        chdir(__DIR__); // Set working directory
        include $filepath;
        return true;
    }

    // Serve static file
    header('Content-Type: ' . $mimeType);
    readfile($filepath);
    return true;
}

// File not found or is PHP - let the built-in server handle it
return false;
