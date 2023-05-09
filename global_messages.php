<?php
session_start();
if (!isset($_SESSION['is_logged_in'])) {
  echo "<script> window.alert('You are not logged in.'); 
    window.location='login.php'; </script>";
  exit();
}

include 'dbconnect.php';
include 'keep_online.php';

$username = $_SESSION["user_logged_in"];

echo "<div class='header-chat'>
<i class='icon fa fa-globe' aria-hidden='true'></i>
<p class='name'><b>GLOBAL CHAT</b></p></div>";
// <i class='icon clickable fa fa-download right' aria-hidden='true' onclick='exportChats(\"" . $chat_username . "\")';></i> </div>";

$sql = "SELECT * FROM global_messages  ORDER BY id";
$result = mysqli_query($conn, $sql);

echo '<div class="global-messages-chat">';

while($row=mysqli_fetch_assoc($result))
{
  $content = $row["content"];
  $media = $row["media"];
  $timestamp = strtotime($row["created_at"]);
    $date = date('d-m-Y', $timestamp);
    $time = date('H:i:s', $timestamp);
    $created_at = strval($date) . ' ' . strval($time);
  if($row["uname"]==$username)
  {
    echo '
    <div class="message text-only">
    <div class="response">';
    if(!empty($content))
      echo '<p class="text">'. $content . '</p>';
    if(isset($media))
      echo '<img class="sent_data" src="data:image;base64,' . $media . '"/>';
    
      echo ' </div> </div>
  <p class="response-time time">'. $created_at . '</p> <br> <br>
    ';
  }
  else
  {
    echo '
    <div class="message text-only">
    <div>';
    if(!empty($content))
    echo '<p class="text"><b>'. $row["uname"] . ' : </b>'. $content .'</p>';
    else
    echo '<p class="text"><b>'. $row["uname"] . ' sent an image.</b></p>';

    if(isset($media))
    echo '<img class="receive_data" src="data:image;base64,' . $media . '"/>';
    
    echo ' </div> </div>
  <p class="time">'. $created_at .'</p>
    ';
  }
}

echo '</div>';

// echo '
// <form action="home.php" method="POST" enctype="multipart/form-data">
// <div class="footer-chat">
// <label>
//   <i class="icon fa fa-file clickable" style="font-size:25pt;" aria-hidden="true">
//   <input type="file" name="file" style="display:none">
//   </i>
// </label>
// <input type="text" name="chat_username" value="' . $chat_username . '" style="display:none"></input>
//   <input type="text" class="write-message" placeholder="Type your message here"></input>
//   <label>
//   <i class="icon send fa fa-paper-plane-o clickable" style="bottom:20px;" aria-hidden="true">
//   <button type="submit" style="display:none"></button>
//   </i>
//   </label>
// </div>
// </form>
// ';

echo '
<form>
<div class="footer-chat">
<label>
  <i class="icon fa fa-file clickable" style="font-size:25pt;" aria-hidden="true">
  <input type="file" name="file" style="display:none">
  </i>
</label>
  <input type="text" class="write-message" placeholder="Type your message here"></input>
  
  <i class="icon send fa fa-paper-plane-o clickable" aria-hidden="true" onclick="sendMessage(\'' . $username . '\')"></i>

</div>

';

// <i class="icon send fa fa-paper-plane-o clickable" aria-hidden="true" onclick="transmitMessage(\'' . $chat_username . '\')">
//   </i>

// <i class="icon send fa fa-paper-plane-o clickable" onclick="sendMessage("'. $chat_username .'")" aria-hidden="true">
//   <div class="message">
//     <div class="photo" style="background-image: url(https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1050&q=80);">
//       <div class="online"></div>
//     </div>
//     <p class="text"> Hi, how are you ? </p>
//   </div>

  // <div class="message text-only">
  //   <p class="text"> What are you doing tonight ? Want to go take a drink ?</p>
  // </div>

  // <p class="time"> 14h58</p>

//   <div class="message text-only">
//     <div class="response">
//       <p class="text"> Hey Megan ! It's been a while 😃</p>
//     </div>
//   </div>

  // <div class="message text-only">
  //   <div class="response">
  //     <p class="text"> When can we meet ?</p>
  //   </div>
  // </div>

  // <p class="response-time time"> 15h04</p>

//   <div class="message">
//     <div class="photo" style="background-image: url(https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1050&q=80);">
//       <div class="online"></div>
//     </div>
//     <p class="text"> 9 pm at the bar if possible 😳</p>
//   </div>

//   <!-- <p class="time"> 15h09</p> -->


//   <div class="message text-only">
//     <p class="text"> What are you doing tonight ? Want to go take a drink ?</p>
//   </div>
//   <div class="message text-only">
//     <p class="text"> What are you doing tonight ? Want to go take a drink ?</p>
//   </div>
//   <div class="message text-only">
//     <p class="text"> What are you doing tonight ? Want to go take a drink ?</p>
//   </div>
//   <div class="message text-only">
//     <p class="text"> What are you doing tonight ? Want to go take a drink ?</p>
//   </div>
//   <p class="time"> 14h58</p>
?>