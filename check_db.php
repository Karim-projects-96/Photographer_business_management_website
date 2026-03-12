<?php
$host = 'localhost';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    echo "Connected successfully to MySQL server\n";
    
    $stmt = $pdo->query("SHOW DATABASES");
    $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Databases found: " . implode(", ", $databases) . "\n";
    
    if (in_array('snapbroker_db', $databases)) {
        echo "Database 'snapbroker_db' exists.\n";
    } else {
        echo "Database 'snapbroker_db' DOES NOT exist.\n";
        // Try to create it
        try {
            $pdo->exec("CREATE DATABASE snapbroker_db");
            echo "Successfully created database 'snapbroker_db'.\n";
            
            // Now import database.sql if it exists
            $sqlFile = 'c:\\Users\\hp\\OneDrive\\Documents\\GitHub\\Photographer_business_management_website\\database.sql';
            if (file_exists($sqlFile)) {
                $sql = file_get_contents($sqlFile);
                $pdo->exec("USE snapbroker_db; " . $sql);
                echo "Successfully imported database.sql.\n";
            }
        } catch (PDOException $e) {
            echo "Failed to create/import database: " . $e->getMessage() . "\n";
        }
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
?>
