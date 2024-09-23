<?php 
require_once('config.php');

// Fetch all maintenance requests
$query = "SELECT t.TicketID, t.Description, t.Status, t.Severity, t.DateCreated, u.First_name, u.Lastname 
          FROM ticket t
          JOIN user u ON t.StudentID = u.UserID";

$conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE) or die('Unable to connect to the database');
$results = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Maintenance Requests</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Maintenance Requests</h1>
    <table>
        <tr>
            <th>Ticket ID</th>
            <th>Description</th>
            <th>Status</th>
            <th>Severity</th>
            <th>Date Created</th>
            <th>Student</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($results)): ?>
        <tr>
            <td><?php echo $row['TicketID']; ?></td>
            <td><?php echo $row['Description']; ?></td>
            <td><?php echo $row['Status']; ?></td>
            <td><?php echo $row['Severity']; ?></td>
            <td><?php echo $row['DateCreated']; ?></td>
            <td><?php echo $row['First_name'] . ' ' . $row['Lastname']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php mysqli_close($conn); ?>
</body>
</html>