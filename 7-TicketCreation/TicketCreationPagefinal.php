<?php 
// Start the session to access session variables
session_start();
require_once('config.php'); 
// Redirect to login page if user is not authenticated or does not have the right residence ID
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Check if the user is logged in and has the correct residence ID
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['residence_id'])) {
        // Store the error message in a session variable
        $_SESSION['error'] = "Please login in order to submit ticket request";
        // Redirect to login page
        header("Location: login.php");
        exit();
    }

    // Retrieve and sanitize form input data
    // htmlspecialchars() is used to prevent XSS attacks by escaping special characters
    $severity = htmlspecialchars($_POST['severity']);
    $description = htmlspecialchars($_POST['description']);
    $status = htmlspecialchars($_POST['status']);
    
    // Fetch the residence ID associated with the logged-in user
    $resID = $_SESSION['residence_id'];  

    // Simple SQL query to insert ticket into database
    $sql = "INSERT INTO ticket (severity, description, status, resID) VALUES ('$severity', '$description', '$status', '$resID')";
    
    // Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "Ticket created successfully.";
    } else {
        echo "Error: " . htmlspecialchars($conn->error);
    }

    // Close the database connection
    $conn->close();
} else {
    // Redirect to the ticket creation form if the request method is not POST
    header("Location: create_ticket_form.php");
    exit();
}
$conn->close();
?>