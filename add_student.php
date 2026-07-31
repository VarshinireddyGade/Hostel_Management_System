<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$message = "";

if (isset($_POST['add_student'])) {

    $student_id = (int) $_POST['student_id'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $department = $_POST['department'];
    $year = (int) $_POST['year'];

    try {

        mysqli_begin_transaction($conn);

        /*
        1. AVAILABLE ROOM AUTOMATIC GA FIND CHEYYADAM
        */
        $room_query = "
            SELECT Room_ID, Room_No
            FROM Room
            WHERE Occupied < Capacity
            ORDER BY Room_ID
            LIMIT 1
            FOR UPDATE
        ";

        $room_result = mysqli_query($conn, $room_query);

        if (mysqli_num_rows($room_result) == 0) {
            throw new Exception("No rooms available! All rooms are full.");
        }

        $room = mysqli_fetch_assoc($room_result);

        $room_id = (int) $room['Room_ID'];
        $room_no = $room['Room_No'];


        /*
        2. STUDENT INSERT
        */
        $student_stmt = mysqli_prepare(
            $conn,
            "INSERT INTO Student
            (Student_ID, Name, Email, Phone, Department, Year, Room_ID)
            VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $student_stmt,
            "issssii",
            $student_id,
            $name,
            $email,
            $phone,
            $department,
            $year,
            $room_id
        );

        mysqli_stmt_execute($student_stmt);


        /*
        3. ROOM OCCUPIED COUNT +1
        */
        $room_stmt = mysqli_prepare(
            $conn,
            "UPDATE Room
             SET Occupied = Occupied + 1
             WHERE Room_ID = ?
             AND Occupied < Capacity"
        );

        mysqli_stmt_bind_param(
            $room_stmt,
            "i",
            $room_id
        );

        mysqli_stmt_execute($room_stmt);

        if (mysqli_stmt_affected_rows($room_stmt) !== 1) {
            throw new Exception("Selected room is already full.");
        }


        /*
        4. FEE AUTOMATIC GA CREATE
        Default Hostel Fee = 25000
        Status = Pending
        */

        $fee_amount = 25000;
        $fee_status = "Pending";

        $fee_stmt = mysqli_prepare(
            $conn,
            "INSERT INTO Fee
            (Student_ID, Amount, Status)
            VALUES (?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $fee_stmt,
            "ids",
            $student_id,
            $fee_amount,
            $fee_status
        );

        mysqli_stmt_execute($fee_stmt);


        /*
        5. ANNI SUCCESS AYITHE SAVE
        */
        mysqli_commit($conn);

        $message =
            "Student Added Successfully! "
            . "Room " . $room_no
            . " automatically allocated. "
            . "Fee ₹25,000 created as Pending.";

    } catch (Throwable $e) {

        mysqli_rollback($conn);

        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Add Student</title>

<style>

body {
    font-family: Arial, sans-serif;
    background: #f4f6f9;
    margin: 0;
}

.header {
    background: #333;
    color: white;
    text-align: center;
    padding: 20px;
}

.container {
    width: 450px;
    margin: 30px auto;
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.15);
}

label {
    font-weight: bold;
}

input,
select {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    margin-bottom: 15px;
    box-sizing: border-box;
}

button {
    width: 100%;
    padding: 12px;
    background: #333;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 16px;
}

button:hover {
    background: #555;
}

.message {
    text-align: center;
    font-weight: bold;
    padding: 12px;
    background: #f1f1f1;
    border-radius: 5px;
    margin-bottom: 20px;
}

.info {
    background: #eef2f7;
    padding: 12px;
    margin-bottom: 20px;
    border-radius: 5px;
}

.back {
    display: inline-block;
    margin-bottom: 20px;
    text-decoration: none;
}

</style>

</head>

<body>

<div class="header">
    <h1>Add Student</h1>
</div>

<div class="container">

<a class="back" href="students.php">
    ← Back to Students
</a>

<div class="info">
    <b>Automatic Process:</b><br><br>

    ✓ Available room will be allocated automatically.<br>
    ✓ Room capacity will be checked automatically.<br>
    ✓ Hostel fee ₹25,000 will be created automatically.<br>
    ✓ Initial fee status will be Pending.
</div>

<?php
if ($message != "") {
?>

<div class="message">
    <?php echo htmlspecialchars($message); ?>
</div>

<?php
}
?>

<form method="POST">

<label>Student ID</label>

<input
    type="number"
    name="student_id"
    required
>


<label>Student Name</label>

<input
    type="text"
    name="name"
    required
>


<label>Email</label>

<input
    type="email"
    name="email"
    required
>


<label>Phone</label>

<input
    type="text"
    name="phone"
    required
>


<label>Department</label>

<select name="department" required>

    <option value="CSE">CSE</option>
    <option value="ECE">ECE</option>
    <option value="EEE">EEE</option>
    <option value="MECH">MECH</option>
    <option value="CIVIL">CIVIL</option>

</select>


<label>Year</label>

<select name="year" required>

    <option value="1">1st Year</option>
    <option value="2">2nd Year</option>
    <option value="3">3rd Year</option>
    <option value="4">4th Year</option>

</select>


<button type="submit" name="add_student">
    Add Student
</button>

</form>

</div>

</body>

</html>