<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Hash;

$filePath = __DIR__ . '/storage/app/users.json';
$users = json_decode(file_get_contents($filePath), true);

$adminHash = $users['1']['password'];
$testPassword = 'Admin123';

echo "Testing Password: $testPassword\n";
echo "Hash in file: $adminHash\n";

if (Hash::check($testPassword, $adminHash)) {
    echo "✅ MATCH!\n";
} else {
    echo "❌ NO MATCH!\n";
}
