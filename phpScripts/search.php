<?php
session_start();

$host = 'localhost';
$dbname = 'backend_db';
$dbusername = 'app_user';
$dbpassword = 'Blue2024';

// Testing the query based on GET inputs -- will change to POST for final implementation //
$query = "SELECT * FROM Submission_Summary WHERE ";
$hasMultiple = false;

if (isset($_POST['gene']) && ($_POST['gene'] != "")) {
    $gene = $_POST['gene'];
    $query .= "SubmittedGeneSymbol LIKE '%" . $gene . "%'";
    $hasMultiple = true;
}

if (isset($_POST['classific']) && ($_POST['classific'] != "")) {
    $classific = $_POST['classific'];

    if ($hasMultiple) {
        $query .= " AND ";
    }

    $query .= "ClinicalSignificance LIKE '%" . $classific . "%'";
    $hasMultiple = true;
}

if (isset($_POST['dna']) && ($_POST['dna'] != "")) {
    $dna = $_POST['dna'];

    if ($hasMultiple) {
        $query .= " AND ";
    }

    $query .= "Description LIKE '%c." . $dna . "%'";
    $hasMultiple = true;
}

if (isset($_POST['lab'])) {
    $lab = $_POST['lab'];

    if ($hasMultiple) {
        $query .= " AND ";
    }

    $query .= "Submitter LIKE '%" . $lab . "%'";
    $hasMultiple = true;
}

if (isset($_POST['protein']) && ($_POST['protein'] != "")) {
    $protein = $_POST['protein'];

    if ($hasMultiple) {
        $query .= " AND ";
    }

    $query .= "Description LIKE '%" . $protein . "%'";
    $hasMultiple = true;
}

if (isset($_POST['year']) && ($_POST['year'] != "")) {
    $year = $_POST['year'];

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

    $time = time();
//    setcookie("download_complete", "true", time() + 300, "/", "", true, true);
    setcookie("download_complete", "true", false, "/");
//    $_SESSION['download_complete'] = true;

} else {
    echo "No results found";
}

$conn->close();
exit();
?>
