<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["job_id"]) && isset($_POST["comment_text"])) {
    // Check if user_id is set in the session
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $job_id = intval($_POST["job_id"]);
        $comment_text = htmlspecialchars(trim($_POST["comment_text"]));

        // Database connection parameters
        $servername = "localhost";
        $username = "root";
        $password = "root";  // Default password for MAMP
        $dbname = "jobposting"; // Your database name

        // Create a connection to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check if the connection was successful
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Escape user input to prevent SQL injection
        $comment_text = $conn->real_escape_string($comment_text);

        // SQL query to insert the comment into the 'comments' table
        $sql = "INSERT INTO comments (job_id, user_id, comment_text) VALUES ($job_id, $user_id, '$comment_text')";

        // Execute the query and check if it was successful
        if ($conn->query($sql) === TRUE) {
            // Redirect back to job listings
            header("Location: jobs.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }

        // Close the connection
        $conn->close();
    } else {
        header("Location: login.php?error=not_logged_in");
        exit();
    }
} else {
    header("Location: jobs.php?error=invalid_request");
    exit();
}
?>