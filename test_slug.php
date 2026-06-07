<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Str;

$header = [
    'MACHINE GROUP', 'MACHINE CODE', 'PLATE NO.', 
    'DETAIL DESCRIPTION', 'MODEL', 'CHASSIS NO./SERIAL NO.', 
    'ENGINE TYPE / MODEL', 'ENGINE S.NO',
    'CURRENT STATUS', 'LOCATION'
];

$normalizedHeader = array_map(function ($h) {
    $h = trim($h);
    $h = preg_replace('/^\xEF\xBB\xBF/', '', $h) ?? $h;
    return strtolower(Str::slug($h, '_'));
}, $header);

print_r($normalizedHeader);

$fuzzyMatched = [];
foreach ($header as $original) {
    $fuzzy = strtolower(preg_replace('/[^a-z]/', '', $original));
    $fuzzyMatched[$original] = $fuzzy;
}
print_r($fuzzyMatched);
