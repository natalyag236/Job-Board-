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
        $username = "root";  // Default username for MAMP
        $password = "root";  // Default password for MAMP
        $dbname = "jobposting"; // Your database name

        // Create a connection to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check if the connection was successful
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepared SQL query to insert the comment into the 'comments' table
        $stmt = $conn->prepare("INSERT INTO comments (job_id, user_id, comment_text) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $job_id, $user_id, $comment_text);

        // Execute the query and check if it was successful
        if ($stmt->execute()) {
            // Redirect back to job listings
            header("Location: jobs.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement and the connection
        $stmt->close();
        $conn->close();
    } else {
        echo "User not logged in.";
    }
} else {
    echo "Invalid request.";
}
?>