<?php
session_start();
if (!isset($_SESSION['is_logged_in'])) {
    echo "<script> window.alert('You are not logged in.'); 
    window.location='login.php'; </script>";
    exit();
}

include 'dbconnect.php';

$image = $_FILES['file']['tmp_name'];
$maximum_size = 16777200;

if (is_uploaded_file($image)) {
    $filename = $_FILES["file"]["name"];
    $allowed = ['png', 'jpg', 'gif', 'jpeg'];
    $format = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if(!in_array($format, $allowed))
    {
        die("File format is not valid.");
    }

    $filesize = $_FILES["file"]["size"];

    if($filesize>$maximum_size)
    {
        die("File size should be less than 16 MB.");
    }

    $image = base64_encode(file_get_contents(addslashes($image)));

    $sql = "UPDATE users SET profile_picture = '$image' WHERE username=" . "'" . $_SESSION['user_logged_in'] . "'";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die("Error in updating profile picture: " . mysqli_error($conn));
    } else {
        echo "<script> window.alert('Profile picture updated successfully.'); 
                window.location='profile.php'; </script>";
    }
}


// $statusMsg = '';

// // File upload path
// $targetDir = "uploads/";
// $fileName = basename($_FILES["file"]["name"]);
// $targetFilePath = $targetDir . $fileName;
// $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

// if(isset($_POST["submit"]) && !empty($_FILES["file"]["name"])){
//     // Allow certain file formats
//     $allowTypes = array('jpg','png','jpeg','gif','pdf');
//     if(in_array($fileType, $allowTypes)){
//         // Upload file to server
//         if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
//             // Insert image file name into database
//             $insert = $db->query("INSERT into images (file_name, uploaded_on) VALUES ('".$fileName."', NOW())");
//             if($insert){
//                 $statusMsg = "The file ".$fileName. " has been uploaded successfully.";
//             }else{
//                 $statusMsg = "File upload failed, please try again.";
//             } 
//         }else{
//             $statusMsg = "Sorry, there was an error uploading your file.";
//         }
//     }else{
//         $statusMsg = 'Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.';
//     }
// }else{
//     $statusMsg = 'Please select a file to upload.';
// }

// // Display status message
// echo $statusMsg;
