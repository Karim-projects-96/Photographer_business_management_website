<?php
$host = '127.0.0.1';
$port = '3307';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port", $user, $pass);
    echo "SUCCESSfully connected to MySQL on port $port!\n";
    $stmt = $pdo->query("SHOW DATABASES");
    $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Databases found: " . implode(", ", $databases) . "\n";
} catch (PDOException $e) {
    echo "FAILED to connect on port $port: " . $e->getMessage() . "\n";
}

$port2 = '3306';
try {
    $pdo2 = new PDO("mysql:host=$host;port=$port2", $user, $pass);
    echo "SUCCESSfully connected to MySQL on port $port2!\n";
    $stmt2 = $pdo2->query("SHOW DATABASES");
    $databases2 = $stmt2->fetchAll(PDO::FETCH_COLUMN);
    echo "Databases found: " . implode(", ", $databases2) . "\n";
} catch (PDOException $e) {
    echo "FAILED to connect on port $port2: " . $e->getMessage() . "\n";
}
?>
