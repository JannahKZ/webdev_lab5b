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

// Fetch user data
$sql = "SELECT matric, name, role FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List</title>
    <link rel="stylesheet" href="layout.css">
</head>
<body>
    <div class="container">
        <h1>Users List</h1>
        <table>
            <tr>
                <th>Matric</th>
                <th>Name</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['matric']) . "</td>
                            <td>" . htmlspecialchars($row['name']) . "</td>
                            <td>" . htmlspecialchars($row['role']) . "</td>
                            <td>
                                <div class='button-container'>
                                    <a href='update.php?matric=" . urlencode($row['matric']) . "' class='button button-update'>Update</a>
                                    <a href='delete.php?matric=" . urlencode($row['matric']) . "' class='button button-delete' onclick='return confirm(\"Are you sure you want to delete this user?\");'>Delete</a>
                                </div>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No users found</td></tr>";
            }
            ?>
            <?php
            if (isset($_GET['message'])) {
                echo "<p class='message'>" . htmlspecialchars($_GET['message']) . "</p>";
            }
            ?>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
