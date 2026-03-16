<?php
require_once 'includes/db_connect.php';
try {
    $stmt = $pdo->query("SHOW CREATE TABLE projects");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    echo "Error showing tables: " . $e->getMessage() . "\n";
}
?>
