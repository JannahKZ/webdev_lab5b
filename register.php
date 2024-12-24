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
    <title>L O G I N</title>
    <link rel="stylesheet" href="layout.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h1>Login</h1>
                    </div>
                    <div class="card-body">
                        <form method="post" action="">
                            <div class="mb-3">
                                <input type="text" class="form-control" name="matric" placeholder="Matric Number" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" name="name" placeholder="Name" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" name="password" placeholder="Password" required>
                            </div>
                            <div class="mb-3">
                                <select name="role" required>
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </form>
                        <?php if (isset($error)) { echo "<p class='error text-danger mt-3 text-center'>$error</p>"; } ?>
                    </div>
                    <div class="card-footer text-center">
                        <p>Have an account? <a href="login.php">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
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


        <button type="submit">Register</button>
    </form>
</body>
</html>
