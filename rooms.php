<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM Room";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rooms - Hostel Management</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
        }

        .header {
            background: #333;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .container {
            width: 85%;
            margin: 30px auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 14px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #333;
            color: white;
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 18px;
            background: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>

<div class="header">
    <h1>Room Details</h1>
</div>

<div class="container">

    <a class="back" href="dashboard.php">
        ← Back to Dashboard
    </a>

    <table>

        <tr>
            <th>Room ID</th>
            <th>Room Number</th>
            <th>Capacity</th>
            <th>Occupied</th>
            <th>Available Beds</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>

        <tr>
            <td><?php echo $row['Room_ID']; ?></td>
            <td><?php echo htmlspecialchars($row['Room_No']); ?></td>
            <td><?php echo $row['Capacity']; ?></td>
            <td><?php echo $row['Occupied']; ?></td>

            <td>
                <?php
                echo $row['Capacity'] - $row['Occupied'];
                ?>
            </td>
        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>