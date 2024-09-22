<?php
if (isset($_POST['submit'])) {
    include '../8-PHPTests/config.php';

    // Initializes MySQLi
    $conn = mysqli_init();

    // Test if the CA certificate file can be read
    if (!file_exists($ca_cert_path)) {
        die("CA file not found: " . $ca_cert_path);
    }

    mysqli_ssl_set($conn, NULL, NULL, $ca_cert_path, NULL, NULL);

    // Establish the connection
    mysqli_real_connect($conn, $servername, $username, $password, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL);

    // If connection failed, show the error
    if (mysqli_connect_errno()) {
        die('Failed to connect to MySQL: ' . mysqli_connect_error());
    }

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