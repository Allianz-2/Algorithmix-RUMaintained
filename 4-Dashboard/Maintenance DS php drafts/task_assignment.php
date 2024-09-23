<?php 
require_once('config.php');

// Fetch all unassigned tickets
$query = "SELECT t.TicketID, t.Description, t.Status, m.MaintenanceStaffID, u.First_name, u.Lastname 
          FROM ticket t
          LEFT JOIN assignment a ON t.TicketID = a.TicketID
          JOIN maintenancestaff m ON m.MaintenanceStaffID = a.MaintenanceStaffID
          JOIN user u ON m.UserID = u.UserID
          WHERE a.TicketID IS NULL";

$conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE) or die('Unable to connect to the database');
$results = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Task Assignment</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Task Assignment</h1>
    <table>
        <tr>
            <th>Ticket ID</th>
            <th>Description</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($results)): ?>
        <tr>
            <td><?php echo $row['TicketID']; ?></td>
            <td><?php echo $row['Description']; ?></td>
            <td><?php echo $row['Status']; ?></td>
            <td>
                <form method="post" action="assign_task.php">
                    <input type="hidden" name="ticketID" value="<?php echo $row['TicketID']; ?>">
                    <select name="maintenanceStaffID" required>
                        <?php
                        // Fetch all maintenance staff for selection
                        $staffQuery = "SELECT MaintenanceStaffID, UserID FROM maintenancestaff";
                        $staffResult = mysqli_query($conn, $staffQuery);
                        while ($staffRow = mysqli_fetch_assoc($staffResult)): ?>
                            <option value="<?php echo $staffRow['MaintenanceStaffID']; ?>">
                                <?php echo $staffRow['UserID']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <button type="submit">Assign</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php mysqli_close($conn); ?>
</body>
</html>