<?php
session_start();
include 'db.php';
if (!isset($_SESSION['school_id'])) header("Location: index.php");

$school_id = $_SESSION['school_id'];

// Add teacher
if (isset($_POST['add_teacher'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $conn->query("INSERT INTO teachers (school_id, name, email) VALUES ($school_id, '$name', '$email')");
}

// Remove teacher
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    $conn->query("DELETE FROM teachers WHERE id=$id AND school_id=$school_id");
}

// Toggle permission
if (isset($_GET['toggle'])) {
    $id = $_GET['toggle'];
    $teacher = $conn->query("SELECT * FROM teachers WHERE id=$id")->fetch_assoc();
    $newPerm = $teacher['can_manage_students'] ? 0 : 1;
    $conn->query("UPDATE teachers SET can_manage_students=$newPerm WHERE id=$id");
}

$teachers = $conn->query("SELECT * FROM teachers WHERE school_id=$school_id");
?>

<h2>Manage Teachers</h2>
<form method="post">
    <input name="name" required placeholder="Teacher Name">
    <input name="email" required placeholder="Email">
    <button name="add_teacher">Add Teacher</button>
</form>

<table>
    <tr><th>Name</th><th>Email</th><th>Actions</th></tr>
    <?php while ($row = $teachers->fetch_assoc()): ?>
    <tr>
        <td><?= $row['name'] ?></td>
        <td><?= $row['email'] ?></td>
        <td>
            <a href="?remove=<?= $row['id'] ?>">Remove</a> |
            <a href="?toggle=<?= $row['id'] ?>">
                <?= $row['can_manage_students'] ? "Disable Student Mgmt" : "Enable Student Mgmt" ?>
            </a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
