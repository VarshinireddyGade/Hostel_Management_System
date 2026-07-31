<?php
session_start();
include 'db.php';

$message = "";

if (isset($_POST['login'])) {

    $name = $_POST['name'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM Admin 
            WHERE Name='$name' AND Password='$password'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['admin'] = $name;
        header("Location: dashboard.php");
        exit();
    } else {
        $message = "Invalid Name or Password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hostel Management - Login</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f4f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-box {
            width: 350px;
            background: white;
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        h3 {
            text-align: center;
            color: #666;
            margin-bottom: 25px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 8px 0 15px 0;
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

        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>

<body>

<div class="login-box">

    <h2>Hostel Management System</h2>
    <h3>Admin Login</h3>

    <?php
    if ($message != "") {
        echo "<p class='error'>$message</p>";
    }
    ?>

    <form method="POST">

        <label>Admin Name</label>
        <input type="text"
               name="name"
               placeholder="Enter admin name"
               required>

        <label>Password</label>
        <input type="password"
               name="password"
               placeholder="Enter password"
               required>

        <button type="submit" name="login">
            Login
        </button>

    </form>

</div>

</body>
</html>