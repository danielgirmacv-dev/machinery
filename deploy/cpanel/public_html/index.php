<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$appPath = __DIR__ . '/__REPO_PATH__';

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $appPath . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $appPath . '/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once $appPath . '/bootstrap/app.php')
    ->handleRequest(Request::capture());
