<?php
require_once 'includes/db_connect.php';
try {
    $sql = "CREATE TABLE IF NOT EXISTS `clients` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `full_name` varchar(255) NOT NULL,
      `email` varchar(255) NOT NULL,
      `phone` varchar(20) NOT NULL,
      `password_hash` varchar(255) NOT NULL,
      `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
      PRIMARY KEY (`id`),
      UNIQUE KEY `email` (`email`),
      UNIQUE KEY `phone` (`phone`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
    $pdo->exec($sql);
    echo "Clients table created successfully.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
