<?php
// Include the configuration file
include 'config.php';

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

// Retrieve all users from the database
$sql = "SELECT * FROM user";
$result = $conn->query($sql);

// Check if the query was successful
if ($result === false) {
    die('Error executing query: ' . $conn->error);
}

// Start HTML output
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .user {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .user:last-child {
            border-bottom: none;
        }
        .user-id {
            font-weight: bold;
        }
        .user-info {
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User List</h1>
        <?php
        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo '<div class="user">';
                echo '<div class="user-id">User ID: ' . $row["UserID"] . '</div>';
                echo '<div class="user-info">Username: ' . $row["First_name"] ." " . $row["Lastname"] . '</div>';
                echo '<div class="user-info">Email: ' . $row["email_address"] . '</div>';
                // Add more fields as needed
                echo '</div>';
            }
        } else {
            echo '<p>No users found.</p>';
        }
        ?>
    </div>
</body>
</html>
<?php
mysqli_close($conn);
?>