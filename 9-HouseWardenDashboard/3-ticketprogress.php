<?php
session_start(); // Start the session
require_once('config.php');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE) or die('Unable to connect to the database');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die('You need to log in first.');
}

// Fetch ticket counts for different statuses
$resolvedCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM ticket WHERE Status = 'Resolved' AND StudentID = ".$_SESSION['user_id']))['count'];
$pendingCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM ticket WHERE Status = 'Open' AND StudentID = ".$_SESSION['user_id']))['count'];
$inProgressCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM ticket WHERE Status = 'In Progress' AND StudentID = ".$_SESSION['user_id']))['count'];

// Fetch ticket details
$query = "SELECT TicketID, Description, Status, DateCreated FROM ticket WHERE StudentID = ?"; 
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['user_id']); 
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Progress Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #81589a;
        }
        .charts {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .chart-container {
            width: 45%;
            margin: 0 10px; /* Add some margin for spacing */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #81589a;
            color: #fff;
        }
    </style>
</head>
<body>

<h1>Ticket Progress Dashboard</h1>

<div class="charts">
    <div class="chart-container">
        <canvas id="ticketProgressChart"></canvas>
    </div>
</div>

<table>
    <tr>
        <th>Ticket ID</th>
        <th>Description</th>
        <th>Status</th>
        <th>Date Created</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['TicketID']; ?></td>
            <td><?php echo $row['Description']; ?></td>
            <td><?php echo $row['Status']; ?></td>
            <td><?php echo $row['DateCreated']; ?></td>
        </tr>
    <?php endwhile; ?>
</table>

<script>
    const ctx = document.getElementById('ticketProgressChart').getContext('2d');
    const ticketProgressChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Resolved', 'Pending', 'In Progress'],
            datasets: [{
                label: 'Ticket Status',
                data: [<?php echo $resolvedCount; ?>, <?php echo $pendingCount; ?>, <?php echo $inProgressCount; ?>],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(255, 206, 86, 0.5)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
        }
    });
</script>

</body>
</html>
