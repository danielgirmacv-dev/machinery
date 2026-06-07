<?php
$logFile = 'storage/logs/laravel.log';
if (!file_exists($logFile)) {
    die("Log file not found.\n");
}
$content = file_get_contents($logFile);
$parts = explode('[2026-', $content);
$lastError = end($parts);
echo "[2026-" . $lastError;
