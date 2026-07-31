<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT Leave_Request.Leave_ID,
               Leave_Request.Student_ID,
               Student.Name,
               Leave_Request.From_Date,
               Leave_Request.To_Date,
               Leave_Request.Status
        FROM Leave_Request
        JOIN Student
        ON Leave_Request.Student_ID = Student.Student_ID
        ORDER BY Leave_Request.Leave_ID";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>

<title>Leave Requests</title>

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

.approve-btn {
    display: inline-block;
    background: green;
    color: white;
    padding: 8px 14px;
    text-decoration: none;
    border-radius: 5px;
    margin-right: 5px;
}

.reject-btn {
    display: inline-block;
    background: #c0392b;
    color: white;
    padding: 8px 14px;
    text-decoration: none;
    border-radius: 5px;
}

.pending {
    color: orange;
    font-weight: bold;
}

.approved {
    color: green;
    font-weight: bold;
}

.rejected {
    color: red;
    font-weight: bold;
}

</style>

</head>

<body>

<div class="header">
    <h1>Leave Request Management</h1>
</div>

<div class="container">

<a href="dashboard.php" class="back">
    ← Back to Dashboard
</a>

<table>

<tr>
    <th>Leave ID</th>
    <th>Student ID</th>
    <th>Student Name</th>
    <th>From Date</th>
    <th>To Date</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>

<tr>

    <td>
        <?php echo $row['Leave_ID']; ?>
    </td>

    <td>
        <?php echo $row['Student_ID']; ?>
    </td>

    <td>
        <?php echo htmlspecialchars($row['Name']); ?>
    </td>

    <td>
        <?php echo $row['From_Date']; ?>
    </td>

    <td>
        <?php echo $row['To_Date']; ?>
    </td>

    <td class="<?php echo strtolower($row['Status']); ?>">
        <?php echo htmlspecialchars($row['Status']); ?>
    </td>

    <td>

    <?php if ($row['Status'] == 'Pending') { ?>

        <a
            class="approve-btn"
            href="update_leave.php?id=<?php echo $row['Leave_ID']; ?>&action=approve"
            onclick="return confirm('Approve this leave request?');"
        >
            Approve
        </a>

        <a
            class="reject-btn"
            href="update_leave.php?id=<?php echo $row['Leave_ID']; ?>&action=reject"
            onclick="return confirm('Reject this leave request?');"
        >
            Reject
        </a>

    <?php } elseif ($row['Status'] == 'Approved') { ?>

        <span class="approved">
            Approved ✓
        </span>

    <?php } else { ?>

        <span class="rejected">
            Rejected ✗
        </span>

    <?php } ?>

    </td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>