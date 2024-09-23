<?php 
require_once('config.php');

// Example query to fetch notifications 
$query = "SELECT * FROM notifications"; // Assuming there's a notifications table
$conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE) or die('Unable to connect to the database');
$results = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Notifications</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Notifications</h1>
    <table>
        <tr>
            <th>Notification ID</th>
            <th>Message</th>
            <th>Date</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($results)): ?>
        <tr>
            <td><?php echo $row['NotificationID']; ?></td>
            <td><?php echo $row['Message']; ?></td>
            <td><?php echo $row['Date']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php mysqli_close($conn); ?>
</body>
</html>