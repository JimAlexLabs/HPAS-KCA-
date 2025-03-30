<?php
/**
 * Simple router for the Hospital Management System
 * This file acts as a Laravel-like front controller without modifying the original code
 */

// Define base path
define('BASE_PATH', __DIR__);

// Parse the URL
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);
$path = trim($path, '/');

// Default route
if (empty($path)) {
    $path = 'index.php';
}

// Handle static files
$ext = pathinfo($path, PATHINFO_EXTENSION);
if (in_array($ext, ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'ico', 'woff', 'woff2', 'ttf', 'eot'])) {
    if (file_exists($path)) {
        // Set appropriate Content-Type header
        $content_types = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject'
        ];
        
        if (isset($content_types[$ext])) {
            header('Content-Type: ' . $content_types[$ext]);
        }
        
        readfile($path);
        exit;
    }
}

// Check if the requested file exists
if (file_exists($path) && is_file($path)) {
    // If it's a PHP file, include it
    if (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
        include $path;
        exit;
    }
    
    // Otherwise just read the file
    readfile($path);
    exit;
}

// If we get here, let's try with .php extension
if (file_exists($path . '.php') && is_file($path . '.php')) {
    include $path . '.php';
    exit;
}

// 404 handler
header("HTTP/1.0 404 Not Found");
echo "<h1>404 Not Found</h1>";
echo "<p>The page you requested could not be found.</p>";
echo "<p><a href='/'>Go to Homepage</a></p>"; 