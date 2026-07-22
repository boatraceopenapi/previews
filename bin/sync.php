<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use BOA\Previews\Storage;
use BOA\Previews\Synchronizer;
use Carbon\CarbonImmutable as Carbon;

$version = $argv[1] ?? 'v3';

$today = Carbon::today('Asia/Tokyo');
$todayY = $today->format('Y');
$todayYmd = $today->format('Ymd');

$yesterday = $today->subDay();
$yesterdayY = $yesterday->format('Y');
$yesterdayYmd = $yesterday->format('Ymd');

$payload = [
    'today' => ['previews' => []],
    'yesterday' => ['previews' => []],
];

if ($version === 'v2' || $version === 'v3') {
    $payload['today']['previews'] = Synchronizer::sync($today);
    $payload['yesterday']['previews'] = Synchronizer::sync($yesterday);
}

if ($payload['today']['previews'] !== []) {
    Storage::save("docs/{$version}/{$todayY}/{$todayYmd}.json", $payload['today']);
    Storage::save("docs/{$version}/today.json", $payload['today']);
}

if ($payload['yesterday']['previews'] !== []) {
    Storage::save("docs/{$version}/{$yesterdayY}/{$yesterdayYmd}.json", $payload['yesterday']);
    Storage::save("docs/{$version}/yesterday.json", $payload['yesterday']);
}
