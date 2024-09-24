<?php 
require_once('config.php');

// Fetch all maintenance requests
$query = "SELECT t.TicketID, t.Description, t.DateCreated, u.First_name, u.Lastname 
          FROM ticket t
          JOIN user u ON t.StudentID = u.UserID";

$conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE) or die('Unable to connect to the database');
$results = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hall Secretary Ticket Approval</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Ticket Approval</h1>
    <table>
        <tr>
            <th>Ticket ID</th>
            <th>Date Created</th>
            <th>Student</th>
            <th>Description</th>
            <th>Approve</th>
            <th>Deny</th>
            <th>Add Comment</th>
           
        </tr>
        <?php while ($row = mysqli_fetch_assoc($results)): ?>
                        <tr>
                            <td><?php echo $row['TicketID']; ?></td>
                            <td><?php echo $row['DateCreated']; ?></td> 
                            <td><?php echo $row['First_name'] . ' ' . $row['Lastname']; ?></td>
                            <td><?php echo $row['Description']; ?></td>
                            <td><a href="#">Approve</a></td>
                            <td><a href="#">Deny</a></td>
                            <td><a href="#">Add Comment</a></td>
                           </tr>
                <?php endwhile; ?>
    </table>
    <?php mysqli_close($conn); ?>

<!-- <style> body { font-family: Arial, sans-serif; background-color: #f9f9f9; /* Light background for contrast */ margin: 0; padding: 20px; } h1 { text-align: center; color: #5c4b8a; /* Darker shade of light purple */ } table { width: 100%; border-collapse: collapse; margin-top: 20px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); border-radius: 5px; /* Rounded corners */ overflow: hidden; /* Clip child elements */ } th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; } tr:nth-child(even) { background-color: #eaeaea; /* Light gray for even rows */ } tr:hover { background-color: #d1c4e9; /* Light purple hover effect */ } th { background-color: #ab8cc6; /* Light purple header */ color: white; /* White text for contrast */ font-weight: bold; } /* Add responsive design */ @media (max-width: 768px) { th, td { font-size: 14px; /* Smaller text on mobile */ } h1 { font-size: 24px; /* Smaller title on mobile */ } } </style> -->
</html>