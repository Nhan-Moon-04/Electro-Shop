<?php
/**
 * Script to create missing customer records for existing users
 * Run this once to fix existing users who don't have customer records
 */

require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "Starting to create missing customer records...\n\n";

    // Find all users who don't have a corresponding customer record
    $usersWithoutCustomer = DB::table('users')
        ->leftJoin('customers', 'users.user_id', '=', 'customers.user_id')
        ->whereNull('customers.customer_id')
        ->select('users.user_id', 'users.user_email', 'users.user_name')
        ->get();

    if ($usersWithoutCustomer->isEmpty()) {
        echo "✓ All users already have customer records!\n";
        exit(0);
    }

    echo "Found " . count($usersWithoutCustomer) . " users without customer records:\n";

    foreach ($usersWithoutCustomer as $user) {
        echo "  - User ID: {$user->user_id}, Email: {$user->user_email}, Name: {$user->user_name}\n";
    }

    echo "\nCreating customer records...\n";

    $created = 0;
    foreach ($usersWithoutCustomer as $user) {
        try {
            // Find next available customer_id
            $maxCustomerId = DB::table('customers')->max('customer_id');
            $newCustomerId = max($maxCustomerId + 1, $user->user_id);

            // Make sure customer_id doesn't exist
            while (DB::table('customers')->where('customer_id', $newCustomerId)->exists()) {
                $newCustomerId++;
            }

            DB::table('customers')->insert([
                'customer_id' => $newCustomerId,
                'user_id' => $user->user_id
            ]);
            echo "✓ Created customer record for User ID: {$user->user_id} (customer_id: {$newCustomerId})\n";
            $created++;
        } catch (\Exception $e) {
            echo "✗ Failed to create customer for User ID: {$user->user_id} - Error: {$e->getMessage()}\n";
        }
    }

    echo "\n✓ Successfully created {$created} customer records!\n";
    echo "Done!\n";

} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
