<?php
session_start();
if (!isset($_SESSION['is_logged_in'])) {
  echo "<script> window.alert('You are not logged in.'); 
    window.location='login.php'; </script>";
  exit();
}

include 'dbconnect.php';
include 'keep_online.php';

$chat_username = $_POST["chat_username"];
$username = $_SESSION["user_logged_in"];
$sql = "SELECT * FROM users WHERE username=" . "'" . $chat_username . "'";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
  $chat_fullname = strtoupper($row["fullname"]);
}

echo "<div class='header-chat'>
<i class='icon fa fa-user-o' aria-hidden='true'></i>
<p class='name'><b>{$chat_fullname}</b> ({$chat_username})</p>
<i class='icon clickable fa fa-ellipsis-h right' aria-hidden='true'></i>
</div>";

$sql = "SELECT * FROM messages WHERE (uname = '{$username}' AND cname = '{$chat_username}')
OR (uname = '{$chat_username}' AND cname = '{$username}')";
$result = mysqli_query($conn, $sql);

echo '<div class="messages-chat">';

while($row=mysqli_fetch_assoc($result))
{
  $content = $row["content"];
  $timestamp = strtotime($row["created_at"]);
    $date = date('d-m-Y', $timestamp);
    $time = date('H:i:s', $timestamp);
    $created_at = strval($date) . ' ' . strval($time);
  if($row["uname"]==$username and $row["cname"]==$chat_username)
  {
    echo '
    <div class="message text-only">
    <div class="response">
      <p class="text">'. $content . '</p>
    </div>
  </div>
  <p class="response-time time">'. $created_at . '</p>
    ';
  }
  else
  {
    echo '
    <div class="message text-only">
    <p class="text">'. $content .'</p>
  </div>

  <p class="time">'. $created_at .'</p>
    ';
  }
}

echo '</div>';

echo '
<form>
<div class="footer-chat">
  <i class="icon fa fa-smile-o clickable" style="font-size:25pt;" aria-hidden="true"></i>
  <input type="text" class="write-message" placeholder="Type your message here"></input>
  <i class="icon send fa fa-paper-plane-o clickable" aria-hidden="true" onclick="transmitMessage(\'' . $chat_username . '\')"></i>
</div>
';


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