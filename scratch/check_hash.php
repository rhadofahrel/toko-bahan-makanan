<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Hash;

$password = 'Admin123';
$hash = '$2y$12$f5msCmNstzT16DIOLRJFSOjmnW60Gn3A7nAObP01oMy50brD.RzPO';

echo "Password: $password\n";
echo "Hash: $hash\n";
echo "Match: " . (Hash::check($password, $hash) ? 'YES' : 'NO') . "\n";
