<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || !isset($_GET['action'])) {
    header("Location: leave.php");
    exit();
}

$id = (int) $_GET['id'];
$action = $_GET['action'];

if ($action === 'approve') {
    $status = 'Approved';
} elseif ($action === 'reject') {
    $status = 'Rejected';
} else {
    header("Location: leave.php");
    exit();
}

$stmt = mysqli_prepare(
    $conn,
    "UPDATE Leave_Request
     SET Status = ?
     WHERE Leave_ID = ?"
);

mysqli_stmt_bind_param($stmt, "si", $status, $id);
mysqli_stmt_execute($stmt);

header("Location: leave.php");
exit();
?>