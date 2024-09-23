<?php 
require_once('config.php');

// Example query for performance analytics
$query = "SELECT COUNT(*) as TotalTickets, SUM(CASE WHEN Status = 'Resolved' THEN 1 ELSE 0 END) as ResolvedTickets
          FROM ticket";
$conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE) or die('Unable to connect to the database');
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Performance Analytics</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Performance Analytics</h1>
    <p>Total Tickets: <?php echo $data['TotalTickets']; ?></p>
    <p>Resolved Tickets: <?php echo $data['ResolvedTickets']; ?></p>
    <?php mysqli_close($conn); ?>
</body>
</html>