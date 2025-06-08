<?php
session_start();
include 'db.php';

if (!isset($_SESSION['school_id'])) {
    header("Location: index.php");
    exit;
}

$school_id = $_SESSION['school_id'];

// Secure insert, update, delete functions
function query($conn, $sql, $types, $params) {
    $stmt = $conn->prepare($sql);
    if (!$stmt) die("Prepare failed: " . $conn->error);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_teacher'])) {
        query($conn, "INSERT INTO teachers (school_id, name, email) VALUES (?, ?, ?)", "iss", [$school_id, $_POST['teacher_name'], $_POST['teacher_email']]);
    } elseif (isset($_POST['update_teacher'])) {
        query($conn, "UPDATE teachers SET name=?, email=? WHERE id=? AND school_id=?", "ssii", [$_POST['teacher_name'], $_POST['teacher_email'], $_POST['teacher_id'], $school_id]);
    } elseif (isset($_POST['remove_teacher'])) {
        query($conn, "DELETE FROM teachers WHERE id=? AND school_id=?", "ii", [(int)$_POST['teacher_id'], $school_id]);
    } elseif (isset($_POST['add_student'])) {
        query($conn, "INSERT INTO students (school_id, name, class) VALUES (?, ?, ?)", "iss", [$school_id, $_POST['student_name'], $_POST['student_class']]);
    } elseif (isset($_POST['update_student'])) {
        query($conn, "UPDATE students SET name=?, class=? WHERE id=? AND school_id=?", "ssii", [$_POST['student_name'], $_POST['student_class'], $_POST['student_id'], $school_id]);
    } elseif (isset($_POST['remove_student'])) {
        query($conn, "DELETE FROM students WHERE id=? AND school_id=?", "ii", [(int)$_POST['student_id'], $school_id]);
    } elseif (isset($_POST['add_batch'])) {
        query($conn, "INSERT INTO batches (school_id, name) VALUES (?, ?)", "is", [$school_id, $_POST['batch_name']]);
    } elseif (isset($_POST['update_batch'])) {
        query($conn, "UPDATE batches SET name=? WHERE id=? AND school_id=?", "sii", [$_POST['batch_name'], $_POST['batch_id'], $school_id]);
    } elseif (isset($_POST['remove_batch'])) {
        query($conn, "DELETE FROM batches WHERE id=? AND school_id=?", "ii", [(int)$_POST['batch_id'], $school_id]);
    } elseif (isset($_POST['add_class'])) {
        query($conn, "INSERT INTO classes (school_id, name) VALUES (?, ?)", "is", [$school_id, $_POST['class_name']]);
    } elseif (isset($_POST['update_class'])) {
        query($conn, "UPDATE classes SET name=? WHERE id=? AND school_id=?", "sii", [$_POST['class_name'], $_POST['class_id'], $school_id]);
    } elseif (isset($_POST['remove_class'])) {
        query($conn, "DELETE FROM classes WHERE id=? AND school_id=?", "ii", [(int)$_POST['class_id'], $school_id]);
    }
}

// Fetch records
$teachers = $conn->query("SELECT * FROM teachers WHERE school_id=$school_id");
$students = $conn->query("SELECT * FROM students WHERE school_id=$school_id");
$batches = $conn->query("SELECT * FROM batches WHERE school_id=$school_id");
$classes = $conn->query("SELECT * FROM classes WHERE school_id=$school_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f0f2f5; }
        .dashboard { display: flex; height: 100vh; }
        .sidebar {
            width: 220px; background: #343a40; color: white;
            padding-top: 20px; position: fixed; height: 100%;
        }
        .sidebar h2 { text-align: center; margin-bottom: 20px; }
        .sidebar button {
            width: 100%; padding: 15px; background: none; border: none;
            color: white; text-align: left; font-size: 16px; cursor: pointer;
        }
        .sidebar button:hover { background: #495057; }
        .logout {
            color: red; position: absolute; top: 20px; left: 20px;
            text-decoration: none; font-weight: bold;
        }
        .content { margin-left: 220px; padding: 20px; flex: 1; }
        .section {
            display: none; background: white; padding: 20px;
            margin-bottom: 30px; border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .section.active { display: block; }
        form input[type="text"], input[type="email"] {
            padding: 8px; width: 200px; margin-right: 10px;
            border-radius: 5px; border: 1px solid #ccc;
        }
        form button {
            padding: 8px 12px; background: #007BFF; color: white;
            border: none; border-radius: 5px; cursor: pointer;
        }
        form button:hover { background: #0056b3; }
        table {
            width: 100%; border-collapse: collapse; margin-top: 10px;
        }
        th, td {
            padding: 8px; border: 1px solid #ddd; text-align: left;
        }
        td form { display: flex; gap: 4px; justify-content: flex-end; }
    </style>
</head>
<body>

<div class="dashboard">
    <div class="sidebar">
        <a href="logout.php" class="logout">Logout</a>
        <h2>ITM COLLEGE</h2>
        <button onclick="showSection('teachers')">Teachers</button>
        <button onclick="showSection('students')">Students</button>
        <button onclick="showSection('batches')">Batches</button>
        <button onclick="showSection('classes')">Classes</button>
    </div>

    <div class="content">
        <!-- Teachers Section -->
        <div id="teachers" class="section active">
            <h2>Teachers</h2>
            <form method="post">
                <input type="text" name="teacher_name" placeholder="Name" required>
                <input type="email" name="teacher_email" placeholder="Email" required>
                <button type="submit" name="add_teacher">Add</button>
            </form>
            <table>
                <tr><th>ID</th><th>Name</th><th>Email</th><th style="text-align:right;">Actions</th></tr>
                <?php while ($t = $teachers->fetch_assoc()): ?>
                <tr>
                    <form method="post">
                        <td><?= $t['id'] ?></td>
                        <td><input type="text" name="teacher_name" value="<?= htmlspecialchars($t['name']) ?>"></td>
                        <td><input type="email" name="teacher_email" value="<?= htmlspecialchars($t['email']) ?>"></td>
                        <td>
                            <input type="hidden" name="teacher_id" value="<?= $t['id'] ?>">
                            <button name="update_teacher">Update</button>
                            <button name="remove_teacher">Remove</button>
                        </td>
                    </form>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <!-- Students Section -->
        <div id="students" class="section">
            <h2>Students</h2>
            <form method="post">
                <input type="text" name="student_name" placeholder="Name" required>
                <input type="text" name="student_class" placeholder="Class" required>
                <button type="submit" name="add_student">Add</button>
            </form>
            <table>
                <tr><th>ID</th><th>Name</th><th>Class</th><th style="text-align:right;">Actions</th></tr>
                <?php while ($s = $students->fetch_assoc()): ?>
                <tr>
                    <form method="post">
                        <td><?= $s['id'] ?></td>
                        <td><input type="text" name="student_name" value="<?= htmlspecialchars($s['name']) ?>"></td>
                        <td><input type="text" name="student_class" value="<?= htmlspecialchars($s['class']) ?>"></td>
                        <td>
                            <input type="hidden" name="student_id" value="<?= $s['id'] ?>">
                            <button name="update_student">Update</button>
                            <button name="remove_student">Remove</button>
                        </td>
                    </form>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <!-- Batches Section -->
        <div id="batches" class="section">
            <h2>Batches</h2>
            <form method="post">
                <input type="text" name="batch_name" placeholder="Batch Name" required>
                <button type="submit" name="add_batch">Add</button>
            </form>
            <table>
                <tr><th>ID</th><th>Name</th><th style="text-align:right;">Actions</th></tr>
                <?php while ($b = $batches->fetch_assoc()): ?>
                <tr>
                    <form method="post">
                        <td><?= $b['id'] ?></td>
                        <td><input type="text" name="batch_name" value="<?= htmlspecialchars($b['name']) ?>"></td>
                        <td>
                            <input type="hidden" name="batch_id" value="<?= $b['id'] ?>">
                            <button name="update_batch">Update</button>
                            <button name="remove_batch">Remove</button>
                        </td>
                    </form>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <!-- Classes Section -->
        <div id="classes" class="section">
            <h2>Classes</h2>
            <form method="post">
                <input type="text" name="class_name" placeholder="Class Name" required>
                <button type="submit" name="add_class">Add</button>
            </form>
            <table>
                <tr><th>ID</th><th>Name</th><th style="text-align:right;">Actions</th></tr>
                <?php while ($c = $classes->fetch_assoc()): ?>
                <tr>
                    <form method="post">
                        <td><?= $c['id'] ?></td>
                        <td><input type="text" name="class_name" value="<?= htmlspecialchars($c['name']) ?>"></td>
                        <td>
                            <input type="hidden" name="class_id" value="<?= $c['id'] ?>">
                            <button name="update_class">Update</button>
                            <button name="remove_class">Remove</button>
                        </td>
                    </form>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</div>

<script>
function showSection(id) {
    document.querySelectorAll('.section').forEach(sec => sec.classList.remove('active'));
    document.getElementById(id).classList.add('active');
}
</script>

</body>
</html>
