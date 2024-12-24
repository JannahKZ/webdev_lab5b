<?php
session_start();
// Ensure the user is logged in
if (!isset($_SESSION['matric'])) {
    header("Location: login.php");
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "Lab_5b");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$matric = $_GET['matric'] ?? null;
$name = $role = $message = "";

// Fetch user details if matric is provided
if ($matric) {
    $sql = "SELECT name, role FROM users WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $name = $user['name'];
        $role = $user['role'];
    } else {
        $message = "User not found.";
    }
    $stmt->close();
}

// Update user details on form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $role = $_POST['role'];

    $sql = "UPDATE users SET name = ?, role = ? WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $role, $matric);

    if ($stmt->execute()) {
        $message = "User updated successfully!";
    } else {
        $message = "Error updating user: " . $stmt->error;
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
    <title>Update User</title>
    <link rel="stylesheet" href="layout.css">
</head>
<body>
    <div class="container">
        <h1>Update User</h1>
        <?php if ($message) echo "<p class='message'>$message</p>"; ?>
        <?php if ($matric && $name): ?>
        <form method="post" action="">
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required><br><br>

            <label for="role">Role:</label>
            <select name="role" required>
                <option value="admin" <?php if ($role === "admin") echo "selected"; ?>>Admin</option>
                <option value="user" <?php if ($role === "user") echo "selected"; ?>>User</option>
            </select><br><br>

            <button type="submit">Update</button>
        </form>
        <?php else: ?>
            <p>No user selected for update.</p>
        <?php endif; ?>
    </div>
</body>
</html>
