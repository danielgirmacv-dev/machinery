<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// ── Adjust this path if your repo lives elsewhere on cPanel ──
// Common locations:
//   ../repositories/machinery
//   ../repositories/pems
//   ../machinery
$appPath = __DIR__ . '/../repositories/machinery';

if (! file_exists($appPath . '/vendor/autoload.php')) {
    http_response_code(500);
    echo '<h1>Laravel bootstrap failed</h1>';
    echo '<p>Cannot find vendor at: <code>' . htmlspecialchars($appPath) . '</code></p>';
    echo '<p>Edit <code>public_html/index.php</code> and set the correct <code>$appPath</code>.</p>';
    exit;
}

if (file_exists($maintenance = $appPath . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

require $appPath . '/vendor/autoload.php';

(require_once $appPath . '/bootstrap/app.php')
    ->handleRequest(Request::capture());
