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
            <a href="index.html" class="nav_logo">Trojan Job Board</a>
            <ul class="nav_link">
                <li><a href="#">Jobs</a></li>
                <li><a href="post.html">Post Jobs</a></li>
                <li><a href="favorite-job.php">Saved</a></li>
                <li><a href="login.html" class="button" id="btn">Login/Sign-Up</a></li>
            </ul>
        </nav>
    </header>

    <section id="job-listings">
        <h1>Available Job Postings</h1>

        <?php
        session_start();

        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "jobposting";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT job_id, job_title, company_name, job_location, job_type, salary, job_description FROM jobs";
        $result = $conn->query($sql);

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

                if (isset($_SESSION['user_id'])) {
                    $user_id = $_SESSION['user_id'];

                    // Check if the job is a favorite
                    $fav_stmt = $conn->prepare("SELECT 1 FROM favorites WHERE user_id = ? AND job_id = ?");
                    $fav_stmt->bind_param("ii", $user_id, $job_id);
                    $fav_stmt->execute();
                    $fav_result = $fav_stmt->get_result();
                    $is_favorite = $fav_result->num_rows > 0;
                    $fav_stmt->close();

                    echo "<form action='favorite-job.php' method='POST' style='display:inline;'>";
                    echo "<input type='hidden' name='job_id' value='$job_id'>";
                    echo "<button type='submit'>" . ($is_favorite ? "❤️" : "♡") . "</button>";
                    echo "</form>";

                    echo "<form action='delete_job.php' method='POST' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this job?\");'>";
                    echo "<input type='hidden' name='job_id' value='$job_id'>";
                    echo "<button type='submit'>Delete</button>";
                    echo "</form>";

                    // Fetch comments
                    $comment_stmt = $conn->prepare("SELECT c.comment_text, u.username, c.created_at FROM comments c JOIN users u ON c.user_id = u.user_id WHERE c.job_id = ? ORDER BY c.created_at DESC");
                    $comment_stmt->bind_param("i", $job_id);
                    $comment_stmt->execute();
                    $comment_result = $comment_stmt->get_result();

                    echo "<div class='comments-section'>";
                    echo "<h3>Comments:</h3>";

                    if ($comment_result->num_rows > 0) {
                        while ($comment_row = $comment_result->fetch_assoc()) {
                            echo "<div class='comment'>";
                            echo "<p><strong>" . htmlspecialchars($comment_row["username"]) . "</strong>: " . htmlspecialchars($comment_row["comment_text"]) . " <em>(" . $comment_row["created_at"] . ")</em></p>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No comments yet.</p>";
                    }
                    echo "</div>";

                    // Comment form
                    echo "<div class='comment-form'>";
                    echo "<form action='submit_commet.php' method='POST'>";
                    echo "<input type='hidden' name='job_id' value='$job_id'>";
                    echo "<textarea name='comment_text' placeholder='Add a comment...' required></textarea>";
                    echo "<button type='submit' class='comment-btn'>Comment</button>";
                    echo "</form>";
                    echo "</div>";
                }

                echo "</div><hr>";
            }
        } else {
            echo "<p>No jobs found.</p>";
        }

        $conn->close();
        ?>
    </section>
</body>
</html>