
<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $Telephone = $_POST['Telephone'];
    $college_id = $_POST['college_id'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $city = $_POST['city'];
    $pincode = $_POST['pincode'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    

    

    $check = $conn->query("SELECT * FROM schools WHERE email='$email'");
    if ($check->num_rows > 0) {
        $error = "Email already registered.";
    } else {
        $conn->query("INSERT INTO schools (name, email, password, mobile,Telephone, college_id, state, district, city, pincode, address) VALUES ('$name', '$email', '$password','$mobile', '$Telephone', '$college_id', '$state', '$district', '$city','$pincode', '$address')");
        $success = "Registration successful. You can now login.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Register College</title>
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
      box-shadow: 0 4px 10px rgba(55, 21, 21, 0.1);
      width: 320px;
    }
    h2 { text-align: center; color: #333; }
    input[type="text"],
    input[type="email"],
    input[type="password"],
    input[type="mobile"],
    input[type="Telephone"],
    input[type="college_id"],
    input[type="state"],
    input[type="district"],
    input[type="city"],
    input[type="pincode"],
    input[type="address"],
    input[type="submit"] {
      width: 100%;
      padding: 4px;
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
    .success { color: green; }
  </style>
</head>
<body>

<form method="post">
  
  <h2>Register New College</h2>
  <input type="text" name="name" placeholder="College Name" required>
  <input type="email" name="email" placeholder="Email" required>
  <input type="password" name="password" placeholder="Password" required>
  <input type="mobile" name="mobile" placeholder="mobile" required>
  <input type="Telephone" name="Telephone" placeholder="Telephone" required>
  <input type="college_id" name="college_id" placeholder="college_id" required>
  <input type="state" name="state" placeholder="state" required>
  <input type="district" name="district" placeholder="district" required>
  <input type="city" name="city" placeholder="city" required>
  <input type="pincode" name="pincode" placeholder="pincode" required>
  <input type="address" name="address" placeholder="address" required>
  <input type="submit" value="Register">
  <p><a href="index.php">Back to Login</a></p>
  <?php
  if (isset($error)) echo "<p class='error'>$error</p>";
  if (isset($success)) echo "<p class='success'>$success</p>";
  ?>
</form>

</body>
</html>
