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

// Check if matric is provided
if (isset($_GET['matric'])) {
    $matric = $_GET['matric'];

    // Prepare the SQL statement to delete the user
    $sql = "DELETE FROM users WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matric);

    // Execute the deletion
    if ($stmt->execute()) {
        header("Location: display.php?message=User deleted successfully");
        exit;
    } else {
        echo "Error deleting user: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Matric number not provided.";
}

$conn->close();
?>
