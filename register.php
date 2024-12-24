<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "Lab_5b");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    // Check if matric already exists
    $checkSql = "SELECT matric FROM users WHERE matric = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("s", $matric); // Changed to "s" for string
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Error: Matric number already exists. Please use a unique matric number.";
    } else {
        // Insert new user
        $sql = "INSERT INTO users (matric, name, role, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $matric, $name, $role, $password); // Changed to "ssss" for strings

        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="layout.css">
</head>
<body>
    <h1>Register</h1>
    <form method="post" action="">
        <label for="matric">Matric Number:</label>
        <input type="text" name="matric" required><br><br> <!-- Changed to type="text" -->

        <label for="name">Name:</label>
        <input type="text" name="name" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br><br>

        <label for="role">Role:</label>
        <select name="role" required>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select><br><br>

        <button type="submit">Register</button>
    </form>
</body>
</html>
