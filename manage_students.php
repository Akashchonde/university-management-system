<?php
session_start();
include 'db.php';

// Validate teacher
$teacher_id = $_SESSION['teacher_id'] ?? null;
$teacher = $conn->query("SELECT * FROM teachers WHERE id=$teacher_id")->fetch_assoc();
if (!$teacher || !$teacher['can_manage_students']) die("Access Denied");

// Add student
if (isset($_POST['add_student'])) {
    $name = $_POST['name'];
    $branch = $_POST['branch'];
    $class = $_POST['class'];
    $conn->query("INSERT INTO students (teacher_id, name, branch, class) VALUES ($teacher_id, '$name', '$branch', '$class')");
}

// Remove student
if (isset($_GET['remove'])) {
    $conn->query("DELETE FROM students WHERE id=" . $_GET['remove'] . " AND teacher_id=$teacher_id");
}

$students = $conn->query("SELECT * FROM students WHERE teacher_id=$teacher_id");
?>

<h2>Manage Students</h2>
<form method="post">
    <input name="name" required placeholder="Student Name">
    <input name="branch" required placeholder="Branch">
    <input name="class" required placeholder="Class">
    <button name="add_student">Add Student</button>
</form>

<table>
    <tr><th>Name</th><th>Branch</th><th>Class</th><th>Action</th></tr>
    <?php while ($row = $students->fetch_assoc()): ?>
    <tr>
        <td><?= $row['name'] ?></td>
        <td><?= $row['branch'] ?></td>
        <td><?= $row['class'] ?></td>
        <td><a href="?remove=<?= $row['id'] ?>">Remove</a></td>
    </tr>
    <?php endwhile; ?>
</table>
