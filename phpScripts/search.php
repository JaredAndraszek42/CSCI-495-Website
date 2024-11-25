<?php
$host = 'localhost';
$dbname = 'backend_db';
$dbusername = 'root';
$dbpassword = 'kali';

echo "<h1> Testing Search </h1>";

// Testing the query based on GET inputs -- will change to POST for final implementation //
$query = "SELECT * FROM table WHERE ";
$hasMultiple = false;

if (isset($_GET['gene_name'])) {
    $gene_name = $_GET['gene_name'];
    $query = $query . "SubmittedGeneSymbol LIKE '%" . $gene_name . "%'";
    $hasMultiple = true;
}

if (isset($_GET['classification'])) {
    $classification = $_GET['classification'];

    if ($hasMultiple == true) {
        $query = $query . " AND ";
    }

    $query = $query . "ClinicalSignificance LIKE '%" . $classification . "%'";
    $hasMultiple = true;
}

if (isset($_GET['dna_change'])) {
    $dna_change = $_GET['dna_change'];

    if ($hasMultiple == true) {
        $query = $query . " AND ";
    }

    $query = $query . "decsription LIKE '%c." . $dna_change . "%'";
    $hasMultiple = true;
}

if (isset($_GET['lab'])) {
    $lab = $_GET['lab'];

    if ($hasMultiple == true) {
        $query = $query . " AND ";
    }

    $query = $query . "submitter LIKE '%" . $lab . "%'";
    $hasMultiple = true;
}

if (isset($_GET['protein_change'])) {
    $protein_change = $_GET['protein_change'];

    if ($hasMultiple == true) {
        $query = $query . " AND ";
    }

    $query = $query . "protien_change LIKE '%" . $protein_change . "%'";
    $hasMultiple = true;
}

if (isset($_GET['year'])) {
    $year = $_GET['year'];

    if ($hasMultiple == true) {
        $query = $query . " AND ";
    }

    $query = $query . "DateLastEvaluated LIKE '%" . $year . "%'";
}

$query = $query . ";";

echo "Query is: $query <br>";







?>
