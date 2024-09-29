<?php
ob_start();
ini_set('display_errors', 1); // Display errors for debugging
error_reporting(E_ALL);
require_once('config.php');

// Check if the request method is POST and the TicketID is set
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['TicketID'])) {
        echo "TicketID is set: " . htmlspecialchars($_POST['TicketID']);
        // Continue with the database connection and update logic
    } else {
        echo "TicketID is not set.";
    }
} else {
    echo "Invalid request method: " . $_SERVER['REQUEST_METHOD'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RU Maintained Dashboard - Update Ticket</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="Dashboard.css">
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
        h2 {
            text-align: center; /* Centered heading for the update page */
            color: #5c4b8a; /* Slightly different color for distinction */
            margin-bottom: 30px; /* Space below the heading */
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
        .update-form {
            max-width: 600px; /* Max width for the form */
            margin: 0 auto; /* Center the form */
            background-color: #fff; /* White background for contrast */
            padding: 20px; /* Padding around the form */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }
        .update-form label {
            display: block; /* Labels as block elements */
            margin-top: 10px; /* Space above labels */
        }
        .update-form input, .update-form select {
            width: 100%; /* Full width input and select elements */
            padding: 10px; /* Padding inside input/select */
            border: 1px solid #ddd; /* Light border */
            border-radius: 5px; /* Rounded corners */
            margin-top: 5px; /* Space above input/select */
        }
        .btn-update {
            background-color: #81589a; /* Button color */
            color: white; /* Button text color */
            border: none; /* No border */
            padding: 10px 15px; /* Padding */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor on hover */
            margin-top: 15px; /* Space above button */
            transition: background-color 0.3s; /* Smooth background color change */
        }
        .btn-update:hover {
            background-color: #6e4c8c; /* Darker shade on hover */
        }
    </style>
</head>
<body>


    <main>
        <header>
            <div class="header-left">
                <strong>Maintenance Staff Dashboard</strong>
            </div>
            <div class="logo">
                <img src="Images/General/93BA9616-515E-488E-836B-2863B8F66675_share.JPG" alt="RU Maintained Logo">
            </div>
        </header>

        <div class="content">
            <h2>Update Ticket</h2> <!-- Updated heading -->
            <div class="update-form"> <!-- Added a container for the form -->
                <!-- Form for Update -->
                <form action="update-ticket.php" method="post" style="display:inline;">
                    <input type="hidden" name="TicketID" value="<?php echo htmlspecialchars($row['TicketID']); ?>">
                    <label for="description-<?php echo htmlspecialchars($row['TicketID']); ?>">Description:</label>
                    <input type="text" name="Description" id="description-<?php echo htmlspecialchars($row['TicketID']); ?>" value="<?php echo htmlspecialchars($row['Description']); ?>" required>

                    <label for="status-<?php echo htmlspecialchars($row['TicketID']); ?>">Status:</label>
                    <select name="Status" id="status-<?php echo htmlspecialchars($row['TicketID']); ?>" required>
                        <option value="Open" <?php if ($row['Status'] == 'Open') echo 'selected'; ?>>Open</option>
                        <option value="Resolved" <?php if ($row['Status'] == 'Resolved') echo 'selected'; ?>>Resolved</option>
                        <!-- we can add other status options as needed -->
                    </select>

                    <label for="severity-<?php echo htmlspecialchars($row['TicketID']); ?>">Severity:</label>
                    <select name="Severity" id="severity-<?php echo htmlspecialchars($row['TicketID']); ?>" required>
                        <option value="low" <?php if ($row['Severity'] == 'low') echo 'selected'; ?>>Low</option>
                        <option value="medium" <?php if ($row['Severity'] == 'medium') echo 'selected'; ?>>Medium</option>
                        <option value="high" <?php if ($row['Severity'] == 'high') echo 'selected'; ?>>High</option>
                    </select>

                    <button type="submit" class="btn-update">Update</button>
                </form>
            </div>
        </div>
    </main>

    <script>
        function openUpdateForm(ticketID, description, status, severity) {
            document.getElementById('updateTicketID').value = ticketID;
            document.getElementById('updateDescription').value = description;
            document.getElementById('updateStatus').value = status;
            document.getElementById('updateSeverity').value = severity;
            document.getElementById('updateForm').style.display = 'block';
        }
        function confirmLogout() {
            return confirm("Are you sure you want to log out?");
        }
    </script>
</body>
</html>
