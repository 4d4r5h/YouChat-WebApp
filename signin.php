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
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $hashed_password = $row["password"];
        if (password_verify($password, $hashed_password)) {
            $_SESSION['is_logged_in'] = true;
            $_SESSION['user_logged_in'] = $username;

            // $sql = "UPDATE users SET connection_id = current_timestamp WHERE username=" . "'" . $_SESSION['user_logged_in'] . "'";
            // $result = mysqli_query($conn, $sql);
            // if (!$result) {
            //     die("Error in updating timestamp: " . mysqli_error($conn));
            // }

            echo "<script> window.alert('Logged in successfully.');  
    window.location='home.php'; </script>";
        } else {
            die("Incorrect username or password.");
        }
    } else {
        die("Incorrect username or password.");
        //     echo "<script>
        // document.getElementById('alert').style.display='block';
        // </script>";
    }
}

mysqli_close($conn);

?>