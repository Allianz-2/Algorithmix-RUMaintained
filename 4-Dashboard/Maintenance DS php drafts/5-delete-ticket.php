<?php
ob_start();
ini_set('display_errors', 1); // Display errors for debugging
error_reporting(E_ALL);
// require_once('db_connection.php'); 


require '../8-PHPTests/config.php';
        
$conn = mysqli_init(); 
if (!file_exists($ca_cert_path)) {
    die("CA file not found: " . $ca_cert_path);
}
mysqli_ssl_set($conn, NULL, NULL, $ca_cert_path, NULL, NULL);
mysqli_real_connect($conn, $servername, $username, $password, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL);

if (mysqli_connect_errno()) {
    die('Failed to connect to MySQL: ' . mysqli_connect_error());
}



















// Check if the request method is POST and TicketID is set
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['TicketID'])) {
        $ticketID = htmlspecialchars($_POST['TicketID']);
        
        // Prepare the SQL delete statement for the assignment first
        $stmt = $conn->prepare("DELETE FROM assignment WHERE TicketID = ?");
        $stmt->bind_param("s", $ticketID);
        
        // Execute the statement and check if deletion was successful
        if ($stmt->execute()) {
            // Now delete the ticket
            $stmt->close(); // Close the previous statement
            
            // Prepare the SQL delete statement for the ticket
            $stmt = $conn->prepare("DELETE FROM ticket WHERE TicketID = ?");
            $stmt->bind_param("s", $ticketID);

            if ($stmt->execute()) {
                // Redirect to the task assignment page after deletion
                header("Location: tee4.php?message=Ticket+deleted+successfully");
                exit();
            } else {
                echo "Error deleting ticket: " . $stmt->error;
            }
        } else {
            echo "Error deleting assignments: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "TicketID is not set.";
    }
} else {
    echo "Invalid request method: " . $_SERVER['REQUEST_METHOD'];
}

// Close the database connection
$conn->close();
?>
