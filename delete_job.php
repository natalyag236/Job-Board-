<?php
session_start();

// Check if user is logged in (modify this condition based on your authentication logic)
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["job_id"])) {
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "jobposting";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $job_id = $conn->real_escape_string($_POST["job_id"]);

    // Delete corresponding entries in the favorites table
    $sql_favorites = "DELETE FROM favorites WHERE job_id = '$job_id'";
    if ($conn->query($sql_favorites) === TRUE) {
        // Delete the job from the jobs table
        $sql_jobs = "DELETE FROM jobs WHERE job_id = '$job_id'";
        if ($conn->query($sql_jobs) === TRUE) {
            // Redirect back to job listings
            header("Location: jobs.php");
            exit();
        } else {
            echo "Error deleting job: " . $conn->error;
        }
    } else {
        echo "Error deleting favorites: " . $conn->error;
    }

    $conn->close();
}
?>