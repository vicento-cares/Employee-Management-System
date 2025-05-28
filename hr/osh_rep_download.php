<?php
include '../process/conn.php';

// Set the content type to CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="osh_voting.csv"');

// Open output stream
$output = fopen('php://output', 'w');

// Fetch data from the database
$sql = "SELECT * FROM osh_voting;";
$stmt = $conn->query($sql);

// Fetch the column names
$columns = $stmt->fetch(PDO::FETCH_ASSOC);
if ($columns) {
    // Write the column names to the CSV
    // fputcsv($output, array_keys($columns));
    fputcsv($output, ['id', 'voter_id', 'candidate', 'date_voted']);
    // Write the data rows to the CSV
    fputcsv($output, $columns);
    
    // Fetch and write the remaining rows
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, $row);
    }
}

// Close the output stream
fclose($output);
exit();

