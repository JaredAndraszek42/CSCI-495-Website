<?php
$host = 'localhost';
$dbname = 'backend_db';
$dbusername = 'app_user';
$dbpassword = 'Blue2024';

// Testing the query based on GET inputs -- will change to POST for final implementation //
$query = "SELECT * FROM Submission_Summary WHERE ";
$hasMultiple = false;

if (isset($_GET['gene_name']) && ($_GET['gene_name'] != "")) {
    $gene_name = $_GET['gene_name'];
    $query .= "SubmittedGeneSymbol LIKE '%" . $gene_name . "%'";
    $hasMultiple = true;
}

if (isset($_GET['classification']) && ($_GET['classification'] != "")) {
    $classification = $_GET['classification'];

    if ($hasMultiple) {
        $query .= " AND ";
    }

    $query .= "ClinicalSignificance LIKE '%" . $classification . "%'";
    $hasMultiple = true;
}

if (isset($_GET['dna_change']) && ($_GET['dna_change'] != "")) {
    $dna_change = $_GET['dna_change'];

    if ($hasMultiple) {
        $query .= " AND ";
    }

    $query .= "Description LIKE '%c." . $dna_change . "%'";
    $hasMultiple = true;
}

if (isset($_GET['lab'])) {
    $lab = $_GET['lab'];

    if ($hasMultiple) {
        $query .= " AND ";
    }

    $query .= "Submitter LIKE '%" . $lab . "%'";
    $hasMultiple = true;
}

if (isset($_GET['protein_change']) && ($_GET['protein_change'] != "")) {
    $protein_change = $_GET['protein_change'];

    if ($hasMultiple) {
        $query .= " AND ";
    }

    $query .= "Description LIKE '%" . $protein_change . "%'";
    $hasMultiple = true;
}

if (isset($_GET['year']) && ($_GET['year'] != "")) {
    $year = $_GET['year'];

    if ($hasMultiple) {
        $query .= " AND ";
    }

    $query .= "DateLastEvaluated LIKE '%" . $year . "%'";
}

$query .= ";";

// Create connection using mysqli
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Open a file pointer for output (php://output writes directly to the browser)
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="export.csv"');
    $output = fopen('php://output', 'w');

    // Output the column headers (optional, depending on your needs)
    $first_row = $result->fetch_assoc(); // Get the first row
    fputcsv($output, array_keys($first_row)); // Write column headers to CSV

    // Write the data to the CSV file
    mysqli_data_seek($result, 0); // Reset result pointer to the first row
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row); // Write each row to the CSV
    }

    // Close the file pointer (optional for php://output)
    fclose($output);
} else {
    echo "No results found";
}

$conn->close();
?>
