<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT Complaint.Complaint_ID,
               Complaint.Student_ID,
               Student.Name,
               Complaint.Description,
               Complaint.Status
        FROM Complaint
        JOIN Student
        ON Complaint.Student_ID = Student.Student_ID
        ORDER BY Complaint.Complaint_ID";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Complaints</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }

        .header {
            background: #333;
            color: white;
            text-align: center;
            padding: 20px;
        }

        .container {
            width: 90%;
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
            background: #333;
            color: white;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .resolve-btn {
            display: inline-block;
            background: #333;
            color: white;
            padding: 8px 14px;
            text-decoration: none;
            border-radius: 5px;
        }

        .pending {
            color: red;
            font-weight: bold;
        }

        .resolved {
            color: green;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="header">
    <h1>Complaint Management</h1>
</div>

<div class="container">

    <a href="dashboard.php" class="back">
        ← Back to Dashboard
    </a>

    <table>

        <tr>
            <th>Complaint ID</th>
            <th>Student ID</th>
            <th>Student Name</th>
            <th>Description</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>

        <tr>

            <td><?php echo $row['Complaint_ID']; ?></td>

            <td><?php echo $row['Student_ID']; ?></td>

            <td>
                <?php echo htmlspecialchars($row['Name']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($row['Description']); ?>
            </td>

            <td class="<?php echo strtolower($row['Status']); ?>">
                <?php echo htmlspecialchars($row['Status']); ?>
            </td>

            <td>

                <?php if ($row['Status'] == 'Pending') { ?>

                    <a
                        class="resolve-btn"
                        href="resolve_complaint.php?id=<?php echo $row['Complaint_ID']; ?>"
                        onclick="return confirm('Resolve this complaint?');"
                    >
                        Resolve
                    </a>

                <?php } else { ?>

                    <span class="resolved">
                        Resolved ✓
                    </span>

                <?php } ?>

            </td>

        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>