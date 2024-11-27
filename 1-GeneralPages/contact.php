<?php
if (isset($_POST['submit'])) {
    include '../8-PHPTests/connectionAzure.php';

    // Collect form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO contact_us (name, email, message) VALUES (?, ?, ?)");

    // Check if the prepare statement failed
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    // Bind parameters
    $stmt->bind_param("sss", $name, $email, $message);

    // Execute the statement
    if ($stmt->execute()) {
        // If user insertion is successful
        echo "<script>
            alert('Message sent successfully! We will get back to you shortly.');
            window.location.href = '2-ContactUs.html'; // Redirect back to contact page
            </script>";
    } else {
        // If there is an error, set the error message
        $error_message = htmlspecialchars($stmt->error);
        echo "<script>
            alert('Message failed to send: $error_message');
            window.history.back(); // Redirect back to the form or previous page
            </script>";
    }

    // Close the statements and connection
    $stmt->close();
    $conn->close();
}
?>