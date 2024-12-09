<?php
session_start();

require 'config.php';

// Handle CSV download first
if (!isset($_SESSION['download_complete']) || $_SESSION['download_complete'] == false) {
    $query = "SELECT * FROM demo_table WHERE 1=1"; // Base query

    // Add conditions based on POST data
    if (!empty($_POST['gene'])) {
        $gene = $conn->real_escape_string($_POST['gene']);
        $query .= " AND SubmittedGeneSymbol LIKE '%$gene%'";
    }
    if (!empty($_POST['classific'])) {
        $classific = $conn->real_escape_string($_POST['classific']);
        $query .= " AND ClinicalSignificance LIKE '%$classific%'";
    }
    if (!empty($_POST['dna'])) {
        $dna = $conn->real_escape_string($_POST['dna']);
        $query .= " AND Description LIKE '%c.$dna%'";
    }
    if (!empty($_POST['lab'])) {
        $lab = $conn->real_escape_string($_POST['lab']);
        $query .= " AND Submitter LIKE '%$lab%'";
    }
    if (!empty($_POST['protein'])) {
        $protein = $conn->real_escape_string($_POST['protein']);
        $query .= " AND Description LIKE '%$protein%'";
    }
    if (!empty($_POST['year'])) {
        $year = $conn->real_escape_string($_POST['year']);
        $query .= " AND DateLastEvaluated LIKE '%$year%'";
    }

    $query .= ";";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="export.csv"');
        $output = fopen('php://output', 'w');

        // Write column headers
        $first_row = $result->fetch_assoc();
        fputcsv($output, array_keys($first_row));

        // Write data rows
        mysqli_data_seek($result, 0);
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, $row);
        }

        fclose($output);

        // Mark download as complete
        $_SESSION['download_complete'] = true;

        exit; // End script to initiate file download
    } else {
        echo "No results found";
        exit;
    }
}

// Reset session variable after showing HTML
if (isset($_SESSION['download_complete'])) {
    unset($_SESSION['download_complete']);
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../stylesheets/style.css">
    <link rel="stylesheet" href="../stylesheets/footer.css">
    <title>Genetic Data Extractor</title>
</head>
<style>
    a {
        text-decoration: none;
    }

    .container {
        min-height: 600px;
    }
</style>
<body>
    <!-- Top Navigation -->
    <div class="topnav">
        <div class="left">Genetic Data Extractor</div>
        <div class="right">
            <a class="active" href="../index.php">Tool</a>
        </div>
    </div>

    <!-- Main Page -->
    <div class="container">
        <h1>Download Complete</h1>
        <p>Your download has been successfully completed.</p>
        <p>If you need to explore further options, click below to begin a new search.</p>
        <a href="../index.php" class="button pageButton">Search Again</a>
    </div>
</body>
</html>
