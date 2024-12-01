<?php
// Database configuration
$host = 'localhost';
$dbname = 'backend_db';
$dbusername = 'app_user';
$dbpassword = 'Blue2024';

// File config
$fileURL = "https://ftp.ncbi.nlm.nih.gov/pub/clinvar/tab_delimited/hgvs4variation.txt.gz";
$localGzFile = 'hgvs4variation.txt.gz';
$extractedFile = 'hgvs4variation.txt';

try {
    // Step 1: Download the file
    echo "Downloading file...\n";
    $file = fopen($fileURL, 'rb');
    if (!$file) {
        throw new Exception("failed to open the URL: $fileURL");
    }
    $localFile = fopen($localGzFile, 'wb');
    if (!$localFile) {
        throw new Exception("Failed to open local file for writing: $localGzFile");
    }
    stream_copy_to_stream($file, $localFile);
    fclose($file);
    fclose($localFile);
    echo "Download Complete.\n";

    // Step 2: Extract the .gz file
    echo "Extracting the .gz file...\n";
    $gz = gzopen($localGzFile, 'rb');
    if (!$gz) {
         throw new Exception("Failed to open .gz file: $localGzFile");
    }
    $output = fopen($extractedFile, 'wb');
    if (!$output) {
         throw new Exception("Failed to open extracted file for writing: $extractedFile");
    }
    while (!gzeof($gz)) {
        fwrite($output, gzread($gz, 4096));
    }
    gzclose($gz);
    fclose($output);
    echo "Extraction complete.\n";

    // Remove first couple lines //
    echo "Removing the first 15 lines...\n";
    shell_exec('sed -i 1,15d hgvs4variation.txt');

    // Remove first character //
    echo "Removing the first character...\n";
    shell_exec("sed '1s/^.//' hgvs4variation.txt > hgvs4variation2.txt");
    shell_exec("rm hgvs4variation.txt");
    $extractedFile = "hgvs4variation2.txt";

    // Step 3: Create SQL table
    echo "Creating SQL table...\n";

    // Read the first line to determine column names
    $fileHandle = fopen($extractedFile, 'r');
    if (!$fileHandle) {
        throw new Exception("Failed to open extracted file: $extractedFile");
    }
    $headerLine = fgets($fileHandle);
    fclose($fileHandle);

    $columns = array_map(function ($col) {
         return "`" . trim($col) . "` TEXT";
    }, explode("\t", $headerLine));

    $tableName = 'hgvs4variation';
    $createTableSQL = "CREATE TABLE IF NOT EXISTS `$tableName` (" . implode(',', $columns) . ");";
    $truncTableSQL = "TRUNCATE TABLE `$tableName`";

    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Execute table creation query
    $pdo->exec($createTableSQL);
    $pdo->exec($truncTableSQL);

    echo "Table `$tableName` created successfully.\n";

    // Step 4: Import the data into the table
    echo "Importing data into the table...\n";

    $fileHandle = fopen($extractedFile, 'r');
    if (!$fileHandle) {
        throw new Exception("Failed to open extracted file: $extractedFile");
    }

    // Skip the header line
    fgets($fileHandle);

    $insertSQL = "INSERT INTO `$tableName` VALUES (" . str_repeat('?,', count($columns) - 1) . "?);";
    $stmt = $pdo->prepare($insertSQL);

    $pdo->beginTransaction();
    while(($line = fgets($fileHandle)) != false) {
        $data = array_map('trim', explode("\t", $line));
        $stmt->execute($data);
    }
    $pdo->commit();
    fclose($fileHandle);
    echo "Data import completed successfully.\n";

    // Clean up
    unlink($localGzFile);
    unlink($extractedFile);
    echo "Cleanup complete.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
