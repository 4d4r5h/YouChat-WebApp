<?php
session_start();
if (!isset($_SESSION['is_logged_in'])) {
    echo "<script> window.alert('You are not logged in.'); 
    window.location='index.php'; </script>";
    exit();
}

include 'dbconnect.php';

if(empty($_FILES))
{
    die("NULL");
}

$file = $_FILES['file']['tmp_name'];
$maximum_size = 16777200;

if (is_uploaded_file($file)) {
    $filename = $_FILES["file"]["name"];
    $allowed = ['png', 'jpg', 'gif', 'jpeg'];
    $format = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if(!in_array($format, $allowed))
    {
        die("NULL");
    }

    $filesize = $_FILES["file"]["size"];

    if($filesize>$maximum_size)
    {
        die("File size should be less than 16 MB.");
    }

    $file = base64_encode(file_get_contents(addslashes($file)));

   echo $file;
}
else
{
    echo "NULL";
}