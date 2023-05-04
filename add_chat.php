<?php

session_start();
if (!isset($_SESSION['is_logged_in'])) {
    echo "<script> window.alert('You are not logged in.'); 
    window.location='login.php'; </script>";
    exit();
}

include 'dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to_username = $_POST["to_username"];
    $from_username = $_SESSION["user_logged_in"];

    $sql = "SELECT * FROM chats WHERE uname = '{$from_username}' AND cname = '{$to_username}'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result)>0)
    {
        die("User is already in Chats.");
    }
    $sql = "INSERT INTO chats
            (uname, cname, last_message_time, disappearing_mode)
            VALUES ('$from_username', '$to_username', NULL, false);";
        $result = mysqli_query($conn, $sql);
        if ($result) {

            $sql = "INSERT INTO chats
            (uname, cname, last_message_time, disappearing_mode)
            VALUES ('$to_username', '$from_username', NULL, false);";
        $result = mysqli_query($conn, $sql);
        if($result)
        {
            echo "<script> window.alert('User has been added to your Chats.'); 
            window.location='home.php'; </script>";
        }
        else
        {
            die("Error inserting into table: " . mysqli_error($conn));   
        }
        } else {
            // echo "<script>document.getElementById('alert').style.display='block';</script>";
            die("Error inserting into table: " . mysqli_error($conn));
        }
}
?>