<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="header">
        <nav class="nav">
            <a href="index.php" class="nav_logo">Trojan Job Board</a>
            <ul class="nav_link">
                <li><a href="jobs.php">Jobs</a></li>
                <li><a href="post.html">Post Jobs</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="login.html" class="button" id="btn">Login/Sign-Up</a></li>
            </ul>
        </nav>
    </header>

    <section id="job-listings">
        <br>
        <br>
        <h1>Available Job Postings</h1>
        <br>
        <br>
        <?php
    // Start the session
    session_start();
    
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
    
    // Fetch all jobs with additional details
    $sql = "SELECT job_id, job_title, company_name, job_location, job_type, salary, job_description FROM jobs";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $job_id = $row["job_id"];
            $job_title = $row["job_title"];
            $company_name = $row["company_name"];
            $job_location = $row["job_location"];
            $job_type = $row["job_type"];
            $salary = $row["salary"];
            $job_description = $row["job_description"];
    
            echo "<div class='job-listing'>";
            echo "<h2>$job_title</h2>";
            echo "<p><strong>Company:</strong> $company_name</p>";
            echo "<p><strong>Location:</strong> $job_location</p>";
            echo "<p><strong>Job Type:</strong> $job_type</p>";
            echo "<p><strong>Salary:</strong> $$salary</p>";
            echo "<p><strong>Description:</strong> $job_description</p>";
    
            // Check if user is logged in for favorites functionality
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
    
                // Check if this job is a favorite
                $fav_sql = "SELECT * FROM favorites WHERE user_id = $user_id AND job_id = $job_id";
                $fav_result = $conn->query($fav_sql);
                $is_favorite = $fav_result->num_rows > 0;
    
                echo "<form action='favorite-job.php' method='POST'>";
                echo "<input type='hidden' name='job_id' value='$job_id'>";
                echo "<button type='submit'>" . ($is_favorite ? "❤️" : "♡") . "</button>";
                echo "</form>";
            }
    
            echo "</div><hr>";
        }
    } else {
        echo "<p>No jobs found.</p>";
    }
    
    $conn->close();
    ?>