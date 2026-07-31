<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "1234",
    "hostel_management"
);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

?>