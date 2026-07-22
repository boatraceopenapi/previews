<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use BOA\Previews\Storage;
use BOA\Previews\Synchronizer;
use Carbon\CarbonImmutable as Carbon;

$version = $argv[1] ?? 'v3';
$date = $argv[2] ?? 'today';

$date = Carbon::parse($date, 'Asia/Tokyo');
$dateY = $date->format('Y');
$dateYmd = $date->format('Ymd');

$previews = Synchronizer::sync($date);

if ($previews === []) {
    fwrite(STDOUT, "NO_DATA {$dateYmd}\n");
    exit(2);
}

Storage::save("docs/{$version}/{$dateY}/{$dateYmd}.json", ['previews' => $previews]);
echo "OK {$dateYmd}\n";
exit(0);
