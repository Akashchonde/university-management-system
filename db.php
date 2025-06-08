<?php
$conn = new mysqli("localhost", "root", "", "school_db");
$conn->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    
}
?>
