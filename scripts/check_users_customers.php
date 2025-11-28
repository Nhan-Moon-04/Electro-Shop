<?php
/**
 * Script to check users and customers relationship
 */

require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "=== CHECKING USERS AND CUSTOMERS ===\n\n";

    // Get all users
    $users = DB::table('users')->select('user_id', 'user_email', 'user_name')->get();
    echo "Total users: " . count($users) . "\n\n";

    // Get all customers
    $customers = DB::table('customers')->get();
    echo "Total customers: " . count($customers) . "\n\n";

    echo "=== CUSTOMERS TABLE ===\n";
    foreach ($customers as $c) {
        echo "customer_id: {$c->customer_id}, user_id: {$c->user_id}\n";
    }

    echo "\n=== USERS WITHOUT CUSTOMERS ===\n";
    $usersWithoutCustomer = DB::table('users')
        ->leftJoin('customers', 'users.user_id', '=', 'customers.user_id')
        ->whereNull('customers.customer_id')
        ->select('users.user_id', 'users.user_email', 'users.user_name')
        ->get();

    if ($usersWithoutCustomer->isEmpty()) {
        echo "✓ All users have customer records!\n";
    } else {
        foreach ($usersWithoutCustomer as $user) {
            echo "User ID: {$user->user_id}, Email: {$user->user_email}, Name: {$user->user_name}\n";
        }
    }

    echo "\n=== CUSTOMERS WITHOUT USERS ===\n";
    $customersWithoutUser = DB::table('customers')
        ->leftJoin('users', 'customers.user_id', '=', 'users.user_id')
        ->whereNull('users.user_id')
        ->select('customers.customer_id', 'customers.user_id')
        ->get();

    if ($customersWithoutUser->isEmpty()) {
        echo "✓ All customers have user records!\n";
    } else {
        foreach ($customersWithoutUser as $c) {
            echo "customer_id: {$c->customer_id}, user_id: {$c->user_id} (user not found!)\n";
        }
    }

} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
