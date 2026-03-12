<?php
$targets = [
    ['host' => 'localhost', 'user' => 'root', 'pass' => ''],
    ['host' => '127.0.0.1', 'user' => 'root', 'pass' => ''],
    ['host' => 'localhost', 'user' => 'root', 'pass' => 'root'],
    ['host' => '127.0.0.1', 'user' => 'root', 'pass' => 'root'],
];

foreach ($targets as $t) {
    echo "Testing {$t['user']}@{$t['host']} with password '" . ($t['pass'] ? '******' : 'empty') . "'... ";
    try {
        $pdo = new PDO("mysql:host={$t['host']}", $t['user'], $t['pass'], [PDO::ATTR_TIMEOUT => 2]);
        echo "SUCCESS!\n";
        exit(0);
    } catch (PDOException $e) {
        echo "FAILED: " . $e->getMessage() . "\n";
    }
}
?>
