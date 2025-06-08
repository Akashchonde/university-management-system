<?php
session_start();
include 'db.php';

if (!isset($_SESSION['school_id'])) {
    header("Location: index.php");
    exit;
}

$school_id = $_SESSION['school_id'];

// Update Teacher
if (isset($_POST['update_teacher'])) {
    $id = (int)$_POST['teacher_id'];
    $name = $_POST['teacher_name'];
    $email = $_POST['teacher_email'];
    $stmt = $conn->prepare("UPDATE teachers SET name=?, email=? WHERE id=? AND school_id=?");
    $stmt->bind_param("ssii", $name, $email, $id, $school_id);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php");
    exit;
}

// Update Student
if (isset($_POST['update_student'])) {
    $id = (int)$_POST['student_id'];
    $name = $_POST['student_name'];
    $class = $_POST['student_class'];
    $stmt = $conn->prepare("UPDATE students SET name=?, class=? WHERE id=? AND school_id=?");
    $stmt->bind_param("ssii", $name, $class, $id, $school_id);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php");
    exit;
}

// Update Batch
if (isset($_POST['update_batch'])) {
    $id = (int)$_POST['batch_id'];
    $name = $_POST['batch_name'];
    $stmt = $conn->prepare("UPDATE batches SET name=? WHERE id=? AND school_id=?");
    $stmt->bind_param("sii", $name, $id, $school_id);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php");
    exit;
}

// Update Class
if (isset($_POST['update_class'])) {
    $id = (int)$_POST['class_id'];
    $name = $_POST['class_name'];
    $stmt = $conn->prepare("UPDATE classes SET name=? WHERE id=? AND school_id=?");
    $stmt->bind_param("sii", $name, $id, $school_id);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php");
    exit;
}
?>
