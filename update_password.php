<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::first();
$user->user_password = Hash::make('123456');
$user->save();

echo "Password updated for: " . $user->user_email . PHP_EOL;
echo "You can now login with password: 123456" . PHP_EOL;