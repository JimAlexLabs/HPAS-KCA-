<?php
/**
 * Laravel-style server for traditional PHP applications
 */

echo "Hospital Management System Server\n";
echo "--------------------------------\n";
echo "Starting development server on http://localhost:8080\n";
echo "Press Ctrl+C to stop the server\n\n";

// Set document root to current directory
$docRoot = __DIR__;
echo "Document root: {$docRoot}\n";

// Run PHP's built-in server with our router
$command = "php -S localhost:8080 -t {$docRoot} {$docRoot}/index.router.php";
passthru($command); 