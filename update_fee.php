<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: fees.php");
    exit();
}

$id = (int) $_GET['id'];

$stmt = mysqli_prepare(
    $conn,
    "UPDATE Fee
     SET Status = 'Paid'
     WHERE Fee_ID = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);

mysqli_stmt_execute($stmt);

header("Location: fees.php");
exit();
?>