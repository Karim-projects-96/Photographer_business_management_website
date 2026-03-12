<?php
/**
 * SnapBroker SaaS - Database Connection
 * Global inclusion for all portals
 */

$host = 'localhost';
$db   = 'snapbroker_db';
$user = 'root'; // Change to your DB username
$pass = '';     // Change to your DB password
$charset = 'utf8mb4';

$port = '3307';
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // In production, log this error instead of showing it
    die("Database Connection Failed: " . $e->getMessage());
}

/**
 * Global Helper: Generate WhatsApp Link
 * As defined in WhatsApp-Integration.md
 */
function generateWhatsAppLink($phone, $message) {
    // Remove non-numeric characters from phone
    $phone = preg_replace('/[^0-9]/', '', $phone);
    $encodedMsg = urlencode($message);
    return "https://wa.me/{$phone}?text={$encodedMsg}";
}
?>
