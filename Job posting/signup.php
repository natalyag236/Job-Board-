<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Form data
    $username = htmlspecialchars(trim($_POST["username"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

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

    // Check if the username or email already exists
    $check_sql = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo "Username or email already exists. <a href='login.html'>Login here</a>";
    } else {
        // Insert the new user into the database
        $insert_sql = "INSERT INTO users (username, email, password_secure) VALUES ('$username', '$email', '$password')";
        if ($conn->query($insert_sql) === TRUE) {
            echo "Registration successful. <a href='login.html'>Login here</a>";
        } else {
            echo "Error: " . $insert_sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
}
?>