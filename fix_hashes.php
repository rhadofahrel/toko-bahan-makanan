<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Hash;

$filePath = __DIR__ . '/storage/app/users.json';
if (!file_exists($filePath)) {
    die("Error: File not found at $filePath\n");
}

$content = file_get_contents($filePath);
$users = json_decode($content, true);

if (!$users) {
    die("Error: Failed to decode JSON from $filePath\n");
}

echo "Old Admin Hash: " . $users['1']['password'] . "\n";

$newAdminHash = Hash::make('Admin123');
$newUserHash = Hash::make('user123');

$users['1']['password'] = $newAdminHash;
$users['2']['password'] = $newUserHash;

echo "New Admin Hash: " . $newAdminHash . "\n";

$result = file_put_contents($filePath, json_encode($users, JSON_PRETTY_PRINT));

if ($result === false) {
    echo "❌ Failed to write to $filePath\n";
} else {
    echo "✅ Successfully updated $filePath\n";
}
