<?php
session_start();
if (!isset($_SESSION['is_logged_in'])) {
    echo "<script> window.alert('You are not logged in.'); 
    window.location='index.php'; </script>";
    exit();
}

include 'dbconnect.php';

$chat_username = $_POST["cname"];
$username = $_SESSION["user_logged_in"];

$sql = "SELECT * FROM messages WHERE (uname = '{$username}' AND cname = '{$chat_username}')
OR (uname = '{$chat_username}' AND cname = '{$username}')";

$result = mysqli_query($conn, $sql);   

$filename = $chat_username . ".txt";
$file = fopen($filename, 'w');

$count_rows=1;
while ($row = mysqli_fetch_array($result)) {          
    $last = end($row);          
    fwrite($file, $count_rows . " | ");
    $count_rows+=1;

    $num = mysqli_num_fields($result) ;
    for($i = 1; $i < $num; $i++) {  
        if($i==4)
        continue;          
        fwrite($file, $row[$i]);                      
        if ($row[$i] != $last)
            fwrite($file, " | ");
    }                                                                 
    fwrite($file, "\n");
}
fclose($file);

header('Content-Description: File Transfer');
header('Content-Disposition: attachment; filename='.basename($filename));
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filename));
header("Content-Type: text/plain");

readfile($filename);
