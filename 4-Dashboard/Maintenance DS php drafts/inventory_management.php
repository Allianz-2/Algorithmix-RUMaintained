<?php 
require_once('config.php');

// Example query to fetch inventory items (customize as needed)
$query = "SELECT * FROM inventory"; // Assuming there's an inventory table
$conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DATABASE) or die('Unable to connect to the database');
$results = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Inventory Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Inventory Management</h1>
    <table>
        <tr>
            <th>Item ID</th>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($results)): ?>
        <tr>
            <td><?php echo $row['ItemID']; ?></td>
            <td><?php echo $row['ItemName']; ?></td>
            <td><?php echo $row['Quantity']; ?></td>
            <td><button>Edit</button> <button>Delete</button></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php mysqli_close($conn); ?>
</body>
</html>