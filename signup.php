<?php
session_start();
if (isset($_SESSION['is_logged_in'])) {
    echo "<script> window.alert('You are already logged in.'); 
    window.location='home.php'; </script>";
    exit();
}

require_once 'dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $fullname = $_POST["fullname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "SELECT * FROM users WHERE username='$username' OR email='$email'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {
        $sql = "INSERT INTO users
            (username, fullname, email, password)
            VALUES ('$username', '$fullname', '$email', '$hashed_password');";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo "<script> window.alert('Account has been created successfully.'); 
            window.location='index.php'; </script>";
        } else {
            // echo "<script>document.getElementById('alert').style.display='block';</script>";
            die("Error inserting into table: " . mysqli_error($conn));
        }
    } else {
        die("Username or Email already exists.");
    }
}

mysqli_close($conn);

?>