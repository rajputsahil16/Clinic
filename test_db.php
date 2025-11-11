<?php
// Simple database connection test
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'clinic';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Database connection successful!\n";
    
    // Check if tables exist
    $tables = ['patients', 'patient_records'];
    
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "Table '$table' exists\n";
            
            // Count records
            $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
            echo "Records in '$table': $count\n";
            
            // Show sample data
            if ($count > 0) {
                $sample = $pdo->query("SELECT * FROM $table LIMIT 1")->fetch(PDO::FETCH_ASSOC);
                echo "Sample data from '$table': " . json_encode($sample) . "\n";
            }
        } else {
            echo "Table '$table' does NOT exist\n";
        }
        echo "\n";
    }
    
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
}
?>
