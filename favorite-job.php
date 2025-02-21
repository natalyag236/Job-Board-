<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "jobposting";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle adding/removing favorite jobs
if (isset($_POST['job_id'])) {
    $job_id = $_POST['job_id'];

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

    // Redirect to the same page to see the updated list
    header("Location: favorite-job.php");
    exit();
}

// Fetch favorite jobs
$sql = "SELECT j.job_id, j.job_title, j.company_name, j.job_location, j.job_type, j.salary, j.job_description 
        FROM jobs j 
        JOIN favorites f ON j.job_id = f.job_id 
        WHERE f.user_id = $user_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorite Jobs</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="header">
        <nav class="nav">
            <a href="index.html" class="nav_logo">Trojan Job Board</a>
            <ul class="nav_link">
                <li><a href="jobs.php">Jobs</a></li>
                <li><a href="post.html">Post Jobs</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="login.html" class="button" id="btn">Login/Sign-Up</a></li>
            </ul>
        </nav>
    </header>

    <section id="job-listings">
        <h1>Your Favorite Jobs</h1>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $job_id = $row["job_id"];
                $job_title = htmlspecialchars($row["job_title"]);
                $company_name = htmlspecialchars($row["company_name"]);
                $job_location = htmlspecialchars($row["job_location"]);
                $job_type = htmlspecialchars($row["job_type"]);
                $salary = htmlspecialchars($row["salary"]);
                $job_description = htmlspecialchars($row["job_description"]);

                echo "<div class='job-listing'>";
                echo "<h2>$job_title</h2>";
                echo "<p><strong>Company:</strong> $company_name</p>";
                echo "<p><strong>Location:</strong> $job_location</p>";
                echo "<p><strong>Job Type:</strong> $job_type</p>";
                echo "<p><strong>Salary:</strong> $$salary</p>";
                echo "<p><strong>Description:</strong> $job_description</p>";

                echo "<form action='favorite-job.php' method='POST' style='display:inline;'>";
                echo "<input type='hidden' name='job_id' value='$job_id'>";
                echo "<button type='submit'>Remove from Favorites</button>";
                echo "</form>";

                echo "</div><hr>";
            }
        } else {
            echo "<p>No favorite jobs found.</p>";
        }

        $conn->close();
        ?>
    </section>
</body>
</html>