<?php
try {
    $drivers = PDO::getAvailableDrivers();
    echo "Available Drivers: " . implode(", ", $drivers) . "\n";
    $host = 'localhost';
    $db   = 'snapbroker_db';
    $user = 'root';
    $pass = '';
    $dsn = "mysql:host=$host;dbname=$db";
    $pdo = new PDO($dsn, $user, $pass);
    echo "Connection Successful!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
