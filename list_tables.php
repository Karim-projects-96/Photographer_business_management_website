<?php
require_once 'includes/db_connect.php';
try {
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables in snapbroker_db: " . implode(", ", $tables) . "\n";
} catch (PDOException $e) {
    echo "Error showing tables: " . $e->getMessage() . "\n";
}
?>
