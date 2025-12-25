<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Удаляем подписки без user_id
$deleted = DB::table('push_subscriptions')->whereNull('user_id')->delete();
echo "Deleted {$deleted} subscriptions without user_id" . PHP_EOL . PHP_EOL;

$subscriptions = DB::table('push_subscriptions')->get();

echo "=== Push Subscriptions ===" . PHP_EOL;
echo "Total: " . $subscriptions->count() . PHP_EOL . PHP_EOL;

foreach ($subscriptions as $sub) {
    $user = DB::table('users')->where('id', $sub->user_id)->first();
    echo "Subscription ID: {$sub->id}" . PHP_EOL;
    echo "User ID: {$sub->user_id}" . PHP_EOL;
    echo "User Name: " . ($user ? $user->name : 'N/A') . PHP_EOL;
    echo "User Email: " . ($user ? $user->email : 'N/A') . PHP_EOL;
    echo "Endpoint: " . substr($sub->endpoint, 0, 50) . "..." . PHP_EOL;
    echo "---" . PHP_EOL;
}
