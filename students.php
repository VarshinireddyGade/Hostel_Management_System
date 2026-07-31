<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT Student.*, Room.Room_No
        FROM Student
        LEFT JOIN Room
        ON Student.Room_ID = Room.Room_ID
        ORDER BY Student.Student_ID";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Students - Hostel Management</title>

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
            width: 92%;
            margin: 30px auto;
        }

        .top-buttons {
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 18px;
            background: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 8px;
        }

        .btn:hover {
            background: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #333;
            color: white;
        }

        tr:hover {
            background: #f1f1f1;
        }

        .edit-btn {
            display: inline-block;
            padding: 7px 12px;
            background: #333;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 2px;
        }

        .delete-btn {
            display: inline-block;
            padding: 7px 12px;
            background: #b42318;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 2px;
        }

        .edit-btn:hover {
            background: #555;
        }

        .delete-btn:hover {
            background: #8a1c13;
        }
    </style>
</head>

<body>

<div class="header">
    <h1>Student Details</h1>
</div>

<div class="container">

    <div class="top-buttons">

        <a class="btn" href="dashboard.php">
            ← Back to Dashboard
        </a>

        <a class="btn" href="add_student.php">
            + Add Student
        </a>

    </div>

    <table>

        <tr>
            <th>Student ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Department</th>
            <th>Year</th>
            <th>Room No</th>
            <th>Action</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>

        <tr>

            <td>
                <?php echo $row['Student_ID']; ?>
            </td>

            <td>
                <?php echo htmlspecialchars($row['Name']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($row['Email']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($row['Phone']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($row['Department']); ?>
            </td>

            <td>
                <?php echo $row['Year']; ?>
            </td>

            <td>
                <?php echo htmlspecialchars($row['Room_No'] ?? 'Not Assigned'); ?>
            </td>

            <td>

                <a class="edit-btn"
                   href="edit_student.php?id=<?php echo $row['Student_ID']; ?>">
                    Edit
                </a>

                <a class="delete-btn"
                   href="delete_student.php?id=<?php echo $row['Student_ID']; ?>"
                   onclick="return confirm('Are you sure you want to delete this student?');">
                    Delete
                </a>

            </td>

        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>