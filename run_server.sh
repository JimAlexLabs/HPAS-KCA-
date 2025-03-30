#!/bin/bash
echo "Starting PHP development server..."
echo "Press Ctrl+C to stop the server"
echo ""
pkill -f "php -S localhost:8080" 2>/dev/null || true
php -S localhost:8080 -t . index.router.php
