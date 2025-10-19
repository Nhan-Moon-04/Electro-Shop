<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$max = Illuminate\Support\Facades\DB::table('migrations')->max('batch');
$batch = $max ? $max : 1;

Illuminate\Support\Facades\DB::table('migrations')->insert([
    'migration' => '2025_10_19_000001_create_password_reset_tokens_table',
    'batch' => $batch
]);

echo "Inserted migration record\n";
