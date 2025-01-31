<?php
session_start();

if (isset($_SESSION['user_id']) && isset($_POST['job_id'])) {
    $user_id = $_SESSION['user_id'];
    $job_id = $_POST['job_id'];

    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "jobposting";

    // Create a new connection to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the job is already a favorite
    $check_sql = "SELECT * FROM favorites WHERE user_id = $user_id AND job_id = $job_id";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // Remove from favorites
        $delete_sql = "DELETE FROM favorites WHERE user_id = $user_id AND job_id = $job_id";
        $conn->query($delete_sql);
    } else {
        // Add to favorites
        $insert_sql = "INSERT INTO favorites (user_id, job_id) VALUES ($user_id, $job_id)";
        $conn->query($insert_sql);
    }

    $conn->close();
}

header("Location: jobs.php");
exit();
?>