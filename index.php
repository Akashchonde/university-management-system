<?php
session_start(); // MUST be the very first line

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM schools WHERE email='$email'");
    if ($result->num_rows == 1) {
        $school = $result->fetch_assoc();
        if (password_verify($password, $school['password'])) {
            $_SESSION['school_id'] = $school['id'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Email not registered.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Login</title>
  <style>
    body {
      font-family: Arial;
      background: #f5f7fa;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    form {
      background: black;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      width: 320px;
    }
    h2 { text-align: center; color: #333; }
    input[type="email"],
    input[type="password"],
    input[type="submit"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 6px;
      border: 1px solid #ddd;
      font-size: 16px;
    }
    input[type="submit"] {
      background: #007BFF;
      color: white;
      cursor: pointer;
      border: none;
    }
    input[type="submit"]:hover {
      background: #0056b3;
    }
    p { text-align: center; }
    .error { color: red; }
  </style>
</head>
<body>

<form method="post">
  <h2>College Login</h2>
  <input type="email" name="email" placeholder="Email" required>
  <input type="password" name="password" placeholder="Password" required>
  <input type="submit" value="Login">
  <p><a href="register.php">Register New College</a></p>
  <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
</form>

</body>
</html>

