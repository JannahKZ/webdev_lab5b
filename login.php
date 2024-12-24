<?php
session_start();
// Database connection
$conn = new mysqli("localhost", "root", "", "Lab_5b");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $password = $_POST['password'];

    // Check if the matric exists in the database
    $sql = "SELECT matric, password FROM users WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        // Fetch the stored password
        $stmt->bind_result($dbMatric, $dbPassword);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $dbPassword)) {
            // Login successful, set session
            $_SESSION['matric'] = $dbMatric;
            header("Location: display.php"); // Redirect to display page
            exit;
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Matric number not found!";
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
    <title>Login</title>
    <link rel="stylesheet" href="layout.css">
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form method="post" action="">
            <input type="text" name="matric" placeholder="Matric Number" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
    </div>
</body>
</html>
