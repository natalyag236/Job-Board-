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
        
        <h1>Available Job Postings</h1>
         <br>
         <br>
        <?php
        // Database connection parameters
        $servername = "localhost";
        $username = "root";
        $password = "root"; // MAMP default password
        $dbname = "jobposting";

        // Create a connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch job postings from the database
        $sql = "SELECT job_title, company_name, job_location, job_description, job_type, salary FROM jobs";
        $result = $conn->query($sql);

        // Check if there are any results
        if ($result->num_rows > 0) {
            // Output data for each row
            while ($row = $result->fetch_assoc()) {
                echo "<div class='job-post'>";
                echo "<h2>Job Title: " . $row['job_title'] . "</h2>";
                echo "<p><strong>Company:</strong> " . $row['company_name'] . "</p>";
                echo "<p><strong>Location:</strong> " . $row['job_location'] . "</p>";
                echo "<p><strong>Description:</strong> " . $row['job_description'] . "</p>";
                echo "<p><strong>Type:</strong> " . $row['job_type'] . "</p>";
                echo "<p><strong>Salary:</strong> " . $row['salary'] . "</p>";
                echo "</div><br>";
            }
        } else {
            echo "<p>No job postings available at the moment.</p>";
        }

        // Close the connection
        $conn->close();
        ?>
    </section>

    <script src="script.js"></script>
</body>
</html>
