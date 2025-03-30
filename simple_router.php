<?php
// Simple Router for Hospital Management System
$uri = $_SERVER['REQUEST_URI'];

// For the root URL, serve index.php
if ($uri === '/' || $uri === '') {
    include 'index.php';
    exit;
}

// Remove leading slash and query string
$path = trim(parse_url($uri, PHP_URL_PATH), '/');

// Check if file exists
if (file_exists($path)) {
    // For PHP files, include them
    if (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
        include $path;
        exit;
    }
    
    // For other files (CSS, JS, images), just serve them
    $mime_types = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif'
    ];
    
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    if (isset($mime_types[$ext])) {
        header('Content-Type: ' . $mime_types[$ext]);
    }
    
    readfile($path);
    exit;
}

// Try adding .php extension
if (file_exists($path . '.php')) {
    include $path . '.php';
    exit;
}

// 404 Not Found
header("HTTP/1.0 404 Not Found");
echo "<h1>404 Not Found</h1>";
echo "<p>The requested resource was not found on this server.</p>";
echo "<p><a href='/'>Return to Homepage</a></p>"; 