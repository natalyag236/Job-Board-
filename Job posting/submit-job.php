<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and trim user inputs
    $jobTitle = htmlspecialchars(trim($_POST['job-title']));
    $companyName = htmlspecialchars(trim($_POST['company-name']));
    $jobLocation = htmlspecialchars(trim($_POST['job-location']));
    $jobDescription = htmlspecialchars(trim($_POST['job-description']));
    $salary = htmlspecialchars(trim($_POST['salary']));
    $jobType = isset($_POST['job-type']) ? htmlspecialchars(trim($_POST['job-type'])) : "Not specified"; // Optional field

    // Check for empty required fields
    if (empty($jobTitle) || empty($companyName) || empty($jobLocation) || empty($jobDescription) || empty($salary)) {
        echo "<h4 style='color: red;'>All required fields must be filled.</h4>";
        exit();
    }

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

    // Prepared SQL query to insert the form data into the 'jobs' table
    $stmt = $conn->prepare("INSERT INTO jobs (job_title, company_name, job_location, job_description, job_type, salary) VALUES (?, ?, ?, ?, ?, ?)");

    // Bind parameters to the SQL query
    $stmt->bind_param("ssssss", $jobTitle, $companyName, $jobLocation, $jobDescription, $jobType, $salary);

    // Execute the query and check if it was successful
    if ($stmt->execute()) {
        // If successful, display a success message
        echo "<h4 style='color: green;'>Job posting submitted successfully!</h4>";
    } else {
        // If there was an error, display the error message
        echo "<h4 style='color: red;'>Error: " . $stmt->error . "</h4>";
    }

    // Close the statement and the connection
    $stmt->close();
    $conn->close();
}
?>
