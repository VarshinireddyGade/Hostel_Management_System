<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: students.php");
    exit();
}

$id = (int) $_GET['id'];

/* Student + allocated room details */
$stmt = mysqli_prepare(
    $conn,
    "SELECT Student.*, Room.Room_No
     FROM Student
     LEFT JOIN Room
     ON Student.Room_ID = Room.Room_ID
     WHERE Student.Student_ID = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);

if (!$student) {
    die("Student not found.");
}

$message = "";

/* Update student details */
if (isset($_POST['update_student'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $department = $_POST['department'];
    $year = (int) $_POST['year'];

    /*
       Room_ID is NOT updated here.
       Room allocation remains automatic.
    */

    $update = mysqli_prepare(
        $conn,
        "UPDATE Student
         SET Name = ?,
             Email = ?,
             Phone = ?,
             Department = ?,
             Year = ?
         WHERE Student_ID = ?"
    );

    mysqli_stmt_bind_param(
        $update,
        "ssssii",
        $name,
        $email,
        $phone,
        $department,
        $year,
        $id
    );

    if (mysqli_stmt_execute($update)) {

        $message = "Student Updated Successfully!";

        /* Refresh student details */
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $student = mysqli_fetch_assoc($result);

    } else {

        $message = "Unable to update student.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Edit Student</title>

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

.locked {
    background: #e9ecef;
    cursor: not-allowed;
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

.back {
    display: inline-block;
    margin-bottom: 20px;
    text-decoration: none;
}

.message {
    text-align: center;
    font-weight: bold;
    margin-bottom: 20px;
}

.info {
    background: #eef2f7;
    padding: 12px;
    border-radius: 5px;
    margin-bottom: 20px;
}

</style>

</head>

<body>

<div class="header">
    <h1>Edit Student</h1>
</div>

<div class="container">

<a class="back" href="students.php">
    ← Back to Students
</a>

<?php if ($message != "") { ?>

<p class="message">
    <?php echo htmlspecialchars($message); ?>
</p>

<?php } ?>

<div class="info">
    Room allocation is managed automatically by the system.
</div>

<form method="POST">

<label>Student ID</label>

<input
    class="locked"
    type="number"
    value="<?php echo $student['Student_ID']; ?>"
    disabled
>


<label>Name</label>

<input
    type="text"
    name="name"
    value="<?php echo htmlspecialchars($student['Name']); ?>"
    required
>


<label>Email</label>

<input
    type="email"
    name="email"
    value="<?php echo htmlspecialchars($student['Email']); ?>"
    required
>


<label>Phone</label>

<input
    type="text"
    name="phone"
    value="<?php echo htmlspecialchars($student['Phone']); ?>"
    required
>


<label>Department</label>

<select name="department" required>

<?php

$departments = [
    'CSE',
    'ECE',
    'EEE',
    'MECH',
    'CIVIL'
];

foreach ($departments as $dept) {

    $selected =
        ($student['Department'] == $dept)
        ? 'selected'
        : '';

    echo "<option value='$dept' $selected>$dept</option>";
}

?>

</select>


<label>Year</label>

<select name="year" required>

<?php for ($i = 1; $i <= 4; $i++) { ?>

<option
    value="<?php echo $i; ?>"
    <?php
    if ($student['Year'] == $i) {
        echo "selected";
    }
    ?>
>

<?php echo $i; ?> Year

</option>

<?php } ?>

</select>


<label>Allocated Room</label>

<input
    class="locked"
    type="text"
    value="<?php
        echo htmlspecialchars(
            $student['Room_No'] ?? 'Not Assigned'
        );
    ?>"
    disabled
>


<button type="submit" name="update_student">
    Update Student
</button>

</form>

</div>

</body>
</html>