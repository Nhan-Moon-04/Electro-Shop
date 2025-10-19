<?php

// This script boots the Laravel app and creates the password_reset_tokens table
// if it doesn't exist. Run with: php scripts/create_password_reset_table.php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (Schema::hasTable('password_reset_tokens')) {
    echo "Table password_reset_tokens already exists.\n";
    exit(0);
}

Schema::create('password_reset_tokens', function (Blueprint $table) {
    $table->increments('id');
    $table->unsignedInteger('MaNguoiDung');
    $table->string('Token', 100);
    $table->dateTime('ExpireAt');
    $table->boolean('Used')->default(false);
    $table->index('MaNguoiDung');
});

echo "Created table password_reset_tokens successfully.\n";
