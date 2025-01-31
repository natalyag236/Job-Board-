<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   // Form data
$username = htmlspecialchars(trim($_POST["username"]));
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Database connection parameters
$servername = "localhost";
$db_username = "root";  // database username for MAMP
$db_password = "root";  // database password for MAMP
$dbname = "jobposting";

// Create a new connection to the database
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the username exists and verify the password
$check_sql = "SELECT user_id, password_secure FROM users WHERE username = '$username'";
$check_result = $conn->query($check_sql);

if ($check_result->num_rows > 0) {
    $row = $check_result->fetch_assoc();
    if (password_verify($password, $row['password_secure'])) {
        // Set the user_id in the session
        $_SESSION['user_id'] = $row['user_id'];
        // Redirect to jobs.php
        header("Location: jobs.php");
        exit();
    } else {
        echo "Invalid password.";
    }
} else {
    echo "Username not found.";
}

$conn->close();
}
?>
