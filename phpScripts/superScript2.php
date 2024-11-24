<?php
// Database configuration
$host = 'localhost';
$dbname = 'backend_db';
$dbusername = 'root';
$dbpassword = 'kali';

// File configuration
$fileURL = "https://ftp.ncbi.nlm.nih.gov/pub/clinvar/tab_delimited/summary_of_conflicting_interpretations.txt";
$localFile = 'summary_of_conflicting_interpretations.txt';

try {
    // Step 1: Download the file
    echo "Downloading file...\n";
    $file = fopen($fileURL, 'rb');
    if (!$file) {
        throw new Exception("Failed to open the URL: $fileURL");
    }
    $localFileHandle = fopen($localFile, 'wb');
    if (!$localFileHandle) {
        throw new Exception("Failed to open local file for writing: $localFile");
    }
    stream_copy_to_stream($file, $localFileHandle);
    fclose($file);
    fclose($localFileHandle);
    echo "Download complete.\n";

    // Step 2: Create SQL table
    echo "Creating SQL table...\n";

    // Read the first line to determine column names
    $fileHandle = fopen($localFile, 'r');
    if (!$fileHandle) {
        throw new Exception("Failed to open file: $localFile");
    }
    $headerLine = fgets($fileHandle);
    fclose($fileHandle);

    $columns = array_map(function ($col) {
        return "`" . preg_replace('/[^a-zA-Z0-9_]/', '_', trim($col)) . "` TEXT";
    }, explode("\t", $headerLine));

    $tableName = 'Summary_of_Conflicting_Interpretations';
    $createTableSQL = "CREATE TABLE IF NOT EXISTS `$tableName` (" . implode(',', $columns) . ");";
    $truncTableSQL = "TRUNCATE TABLE `$tableName`";

    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Execute table creation query
    $pdo->exec($createTableSQL);
    $pdo->exec($truncTableSQL);
    echo "Table `$tableName` created successfully.\n";

    // Step 3: Import the data into the table
    echo "Importing data into the table...\n";

    $fileHandle = fopen($localFile, 'r');
    if (!$fileHandle) {
        throw new Exception("Failed to open file: $localFile");
    }

    // Skip the header line
    fgets($fileHandle);

    $insertSQL = "INSERT INTO `$tableName` VALUES (" . str_repeat('?,', count($columns) - 1) . "?);";
    $stmt = $pdo->prepare($insertSQL);

    $pdo->beginTransaction();
    while (($line = fgets($fileHandle)) !== false) {
        $data = array_map('trim', explode("\t", $line));
        $stmt->execute($data);
    }
    $pdo->commit();
    fclose($fileHandle);
    echo "Data import completed successfully.\n";

    // Clean up
    unlink($localFile);
    echo "Cleanup complete.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
