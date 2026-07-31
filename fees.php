<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT Fee.Fee_ID,
               Student.Name,
               Fee.Amount,
               Fee.Status
        FROM Fee
        JOIN Student
        ON Fee.Student_ID = Student.Student_ID
        ORDER BY Fee.Fee_ID";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Fees - Hostel Management</title>

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

        .paid {
            color: green;
            font-weight: bold;
        }

        .pending {
            color: red;
            font-weight: bold;
        }

        .pay-btn {
            display: inline-block;
            background: #333;
            color: white;
            padding: 8px 14px;
            text-decoration: none;
            border-radius: 5px;
        }

        .pay-btn:hover {
            background: #555;
        }

        .done {
            color: green;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="header">
    <h1>Fee Details</h1>
</div>

<div class="container">

    <a class="back" href="dashboard.php">
        ← Back to Dashboard
    </a>

    <table>

        <tr>
            <th>Fee ID</th>
            <th>Student Name</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>

        <tr>

            <td>
                <?php echo $row['Fee_ID']; ?>
            </td>

            <td>
                <?php echo htmlspecialchars($row['Name']); ?>
            </td>

            <td>
                ₹<?php echo number_format($row['Amount'], 2); ?>
            </td>

            <td class="<?php echo strtolower($row['Status']); ?>">
                <?php echo htmlspecialchars($row['Status']); ?>
            </td>

            <td>

                <?php if ($row['Status'] == 'Pending') { ?>

                    <a class="pay-btn"
                       href="update_fee.php?id=<?php echo $row['Fee_ID']; ?>"
                       onclick="return confirm('Mark this fee as Paid?');">

                        Mark as Paid

                    </a>

                <?php } else { ?>

                    <span class="done">
                        Paid ✓
                    </span>

                <?php } ?>

            </td>

        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>