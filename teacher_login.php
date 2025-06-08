<?php
session_start();
include 'db.php';

// Check if teacher ID is passed via URL
if (!isset($_GET['id'])) {
    echo "Teacher ID not provided.";
    exit;
}

$teacher_id = intval($_GET['id']);

// Fetch teacher details
$result = $conn->query("SELECT * FROM teachers WHERE id = $teacher_id");

if ($result->num_rows !== 1) {
    echo "Invalid Teacher ID.";
    exit;
}

$teacher = $result->fetch_assoc();

// Store teacher info in session
$_SESSION['teacher_id'] = $teacher['id'];
$_SESSION['teacher_name'] = $teacher['name'];

// Redirect to teacher's dashboard
header("Location: teacher_dashboard.php");
exit;
?>
