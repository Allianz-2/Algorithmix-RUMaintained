<?php
// Check if the form has been submitted
if (isset($_POST['submit'])) {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $message = $_POST['message'];

    // Validate email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address!");
    }

    // Include database configuration file
    require_once("config.php");

    // Create a database connection
    $conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE) or die("Error connecting to the database!");

    // Prepare and execute the SQL query to insert data into the database
    $query = "INSERT INTO contact_requests (name, email, phone, role, message) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'sssss', $name, $email, $phone, $role, $message);
    
    if (mysqli_stmt_execute($stmt)) {
        // Query executed successfully
        echo "<strong style=\"color: green;\">Thank you for contacting us! We'll get back to you soon.</strong>";
    } else {
        // Error executing query
        die("Error submitting the form.");
    }

    // Close the statement and the connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>