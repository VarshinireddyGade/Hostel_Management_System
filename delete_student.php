<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

/* TOTAL STUDENTS */
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM Student");
$total_students = mysqli_fetch_assoc($result)['total'];

/* TOTAL ROOMS */
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM Room");
$total_rooms = mysqli_fetch_assoc($result)['total'];

/* AVAILABLE BEDS */
$result = mysqli_query(
    $conn,
    "SELECT SUM(Capacity - Occupied) AS available FROM Room"
);
$available_beds = mysqli_fetch_assoc($result)['available'] ?? 0;

/* PENDING FEES */
$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM Fee WHERE Status = 'Pending'"
);
$pending_fees = mysqli_fetch_assoc($result)['total'];

/* PENDING COMPLAINTS */
$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM Complaint WHERE Status = 'Pending'"
);
$pending_complaints = mysqli_fetch_assoc($result)['total'];

/* PENDING LEAVES */
$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM Leave_Request WHERE Status = 'Pending'"
);
$pending_leaves = mysqli_fetch_assoc($result)['total'];
?>

<!DOCTYPE html>
<html>
<head>

<title>Hostel Management Dashboard</title>

<style>

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f4f6f9;
}

.header {
    background: #333;
    color: white;
    padding: 20px;
    text-align: center;
}

.container {
    width: 90%;
    margin: 30px auto;
}

.top-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.logout-btn {
    background: #c0392b;
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 5px;
}

/* COUNTERS */

.stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 35px;
}

.stat-card {
    background: white;
    padding: 25px;
    text-align: center;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.stat-card h3 {
    margin-top: 0;
    color: #555;
}

.stat-card h1 {
    font-size: 35px;
    margin-bottom: 0;
}

/* MANAGEMENT CARDS */

.cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.card {
    background: white;
    padding: 30px;
    text-align: center;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.card a {
    display: inline-block;
    background: #333;
    color: white;
    padding: 10px 18px;
    text-decoration: none;
    border-radius: 5px;
}

.card a:hover {
    background: #555;
}

@media(max-width: 800px) {

    .stats,
    .cards {
        grid-template-columns: 1fr;
    }

}

</style>

</head>

<body>

<div class="header">

<h1>Hostel Management System</h1>

<p>Automatic Admin Dashboard</p>

</div>

<div class="container">

<div class="top-section">

<h2>
Welcome,
<?php echo htmlspecialchars($_SESSION['admin']); ?>!
</h2>

<a href="logout.php" class="logout-btn">
Logout
</a>

</div>


<!-- AUTOMATIC COUNTERS -->

<div class="stats">

<div class="stat-card">

<h3>Total Students</h3>

<h1>
<?php echo $total_students; ?>
</h1>

</div>


<div class="stat-card">

<h3>Total Rooms</h3>

<h1>
<?php echo $total_rooms; ?>
</h1>

</div>


<div class="stat-card">

<h3>Available Beds</h3>

<h1>
<?php echo $available_beds; ?>
</h1>

</div>


<div class="stat-card">

<h3>Pending Fees</h3>

<h1>
<?php echo $pending_fees; ?>
</h1>

</div>


<div class="stat-card">

<h3>Pending Complaints</h3>

<h1>
<?php echo $pending_complaints; ?>
</h1>

</div>


<div class="stat-card">

<h3>Pending Leaves</h3>

<h1>
<?php echo $pending_leaves; ?>
</h1>

</div>

</div>


<!-- MANAGEMENT -->

<div class="cards">

<div class="card">

<h2>Students</h2>

<p>Add, edit and manage students.</p>

<a href="students.php">
Manage Students
</a>

</div>


<div class="card">

<h2>Rooms</h2>

<p>Check room capacity and availability.</p>

<a href="rooms.php">
Manage Rooms
</a>

</div>


<div class="card">

<h2>Fees</h2>

<p>View student fee information.</p>

<a href="fees.php">
Manage Fees
</a>

</div>


<div class="card">

<h2>Complaints</h2>

<p>View and resolve complaints.</p>

<a href="complaints.php">
View Complaints
</a>

</div>


<div class="card">

<h2>Leave Requests</h2>

<p>Approve or reject leave requests.</p>

<a href="leave.php">
View Leaves
</a>

</div>

</div>

</div>

</body>
</html>