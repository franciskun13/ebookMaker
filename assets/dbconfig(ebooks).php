<?php
error_reporting(0);
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ebook-maker";
$datatable = "ebooks"; // MySQL table name
$results_per_page = 5; // number of results per page

// Create connection
$conn_ebooks = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn_ebooks->connect_error) {
    die("Connection failed: " . $conn_ebooks->connect_error);
}

?>