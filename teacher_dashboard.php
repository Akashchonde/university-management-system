<?php
session_start();
include 'db.php';

if (!isset($_SESSION['teacher_id'])) { 
    header("Location: index.php");
    exit;
}

$school_id = $_SESSION['school_id'];
$teacher_name = $_SESSION['teacher_name'];

// Handle add, remove, update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_student'])) {
        $name = $conn->real_escape_string($_POST['student_name']);
        $class = $conn->real_escape_string($_POST['student_class']);
        $branch = $conn->real_escape_string($_POST['student_branch']);
        $conn->query("INSERT INTO students (school_id, name, class, branch) VALUES ($school_id, '$name', '$class', '$branch')");
    } elseif (isset($_POST['remove_student'])) {
        $student_id = (int)$_POST['student_id'];
        $conn->query("DELETE FROM students WHERE id=$student_id AND school_id=$school_id");
    } elseif (isset($_POST['update_student'])) {
        $student_id = (int)$_POST['student_id'];
        $name = $conn->real_escape_string($_POST['student_name']);
        $class = $conn->real_escape_string($_POST['student_class']);
        $branch = $conn->real_escape_string($_POST['student_branch']);
        $conn->query("UPDATE students SET name='$name', class='$class', branch='$branch' WHERE id=$student_id AND school_id=$school_id");
    }
}

$edit_id = isset($_GET['edit_id']) ? (int)$_GET['edit_id'] : 0;

// Fetch all students
$query = "SELECT id, name, branch, class FROM students WHERE school_id = $school_id ORDER BY class, name";
$result = $conn->query($query);

if (!$result) {
    die("Error fetching students: " . $conn->error);
}

// Group by class
$students_by_class = [];
while ($row = $result->fetch_assoc()) {
    $students_by_class[$row['class']][] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teacher Dashboard</title>
    <style>
        body { font-family: Arial; padding: 30px; background: #f4f4f4; }
        .class-section, .add-student-form { background: white; padding: 20px; margin-bottom: 25px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        button, input[type="submit"] { padding: 6px 12px; border: none; color: white; border-radius: 5px; cursor: pointer; }
        button.remove-btn { background: #dc3545; }
        button.remove-btn:hover { background: #a71d2a; }
        button.edit-btn { background: #28a745; }
        button.edit-btn:hover { background: #1e7e34; }
        button.update-btn { background: #ffc107; color: black; }
        button.update-btn:hover { background: #e0a800; }
        .inline-form { display: inline; }
        a.logout { float: right; color: red; text-decoration: none; }
        input[type="text"] { padding: 6px; margin-right: 10px; border-radius: 4px; border: 1px solid #ccc; }
    </style>
</head>
<body>

<h1>Welcome, <?= htmlspecialchars($teacher_name) ?> <a class="logout" href="logout.php">Logout</a></h1>

<div class="add-student-form">
    <h2>Add Student</h2>
    <form method="post">
        <input type="text" name="student_name" placeholder="Student Name" required>
        <input type="text" name="student_class" placeholder="Class" required>
        <input type="text" name="student_branch" placeholder="Branch" required>
        <button type="submit" name="add_student" style="background:#007BFF;">Add</button>
    </form>
</div>

<?php foreach ($students_by_class as $class => $students): ?>
    <div class="class-section">
        <h2>Class: <?= htmlspecialchars($class) ?></h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Branch</th>
                <th>Class</th>
                <th>Action</th>
            </tr>
            <?php foreach ($students as $student): ?>
                <?php if ($student['id'] == $edit_id): ?>
                    <!-- Editable row -->
                    <tr>
                        <form method="post">
                            <td><?= $student['id'] ?><input type="hidden" name="student_id" value="<?= $student['id'] ?>"></td>
                            <td><input type="text" name="student_name" pattern="[A-Za-z]{2,}" value="<?= htmlspecialchars($student['name']) ?>" required></td>
                            <td><input type="text" name="student_branch" value="<?= htmlspecialchars($student['branch']) ?>" required></td>
                            <td><input type="text" name="student_class" value="<?= htmlspecialchars($student['class']) ?>" required></td>
                            <td>
                                <button type="submit" name="update_student" class="update-btn">Update</button>
                                <a href="teacher_dashboard.php">Cancel</a>
                            </td>
                        </form>
                    </tr>
                <?php else: ?>
                    <!-- Normal row -->
                    <tr>
                        <td><?= htmlspecialchars($student['id']) ?></td>
                        <td><?= htmlspecialchars($student['name']) ?></td>
                        <td><?= htmlspecialchars($student['branch']) ?></td>
                        <td><?= htmlspecialchars($student['class']) ?></td>
                        <td>
                            <form method="post" class="inline-form" onsubmit="return confirm('Are you sure?');">
                                <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
                                <button type="submit" name="remove_student" class="remove-btn">Remove</button>
                            </form>
                            <form method="get" class="inline-form">
                                <input type="hidden" name="edit_id" value="<?= $student['id'] ?>">
                                <button type="submit" class="edit-btn">Edit</button>
                            </form>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </table>
    </div>
<?php endforeach; ?>

</body>
</html>
