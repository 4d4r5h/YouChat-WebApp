<?php
session_start();
if (!isset($_SESSION['is_logged_in'])) {
  echo "<script> window.alert('You are not logged in.'); 
    window.location='login.php'; </script>";
  exit();
}

include 'dbconnect.php';

$sql = "SELECT * FROM chats WHERE uname=" . "'" . $_SESSION["user_logged_in"] . "'";
$chats_result = mysqli_query($conn, $sql);

$chats = [];

while ($row = mysqli_fetch_assoc($chats_result)) {

  $chat_last_message = "";
  $chat_last_message_time = "∞ min";

  $chat_username = $row["cname"];
  if (isset($row["last_message"]))
    $chat_last_message = $row["last_message"];
  if (isset($row["last_message_time"]))
    $timestamp = strtotime($row["last_message_time"]);
    $date = date('d-m-Y', $timestamp);
    $time = date('H:i:s', $timestamp);
    $chat_last_message_time = strval($date) . ' ' . strval($time);

  $sql = "SELECT * FROM users WHERE username=" . "'" . $chat_username . "'";
  $users_result = mysqli_query($conn, $sql);

  $chat_image = NULL;
  $chat_color = "grey";

  while ($row = mysqli_fetch_assoc($users_result)) {
    $chat_fullname = $row["fullname"];
    if (isset($row["profile_picture"]))
      $chat_image = "data:image;base64," . $row["profile_picture"];
    if (isset($row["connection_id"]))
      $chat_color = "green";
  }

  $chat_data = [
    "last_message" => $chat_last_message, "last_message_time" => $chat_last_message_time,
    "username" => $chat_username, "fullname" => $chat_fullname, "image" => $chat_image, "color" => $chat_color
  ];
  array_push($chats, $chat_data);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>YouChat</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.min.css">
  <link rel="stylesheet" href="home.css">
  <?php include 'keep_online.php'; ?>
</head>

<body>

  <div class="container">
    <div class="row">
      <nav class="menu">
        <ul class="items">
          <li class="item">
            <i class="fa fa-home" aria-hidden="true"></i>
          </li>
          <li class="item">
            <i class="fa fa-user" aria-hidden="true"></i>
          </li>
          <li class="item">
            <i class="fa fa-pencil" aria-hidden="true"></i>
          </li>
          <li class="item item-active">
            <i class="fa fa-commenting" aria-hidden="true"></i>
          </li>
          <li class="item">
            <i class="fa fa-file" aria-hidden="true"></i>
          </li>
          <li class="item">
            <i class="fa fa-cog" aria-hidden="true"></i>
          </li>
        </ul>
      </nav>


      <section class="discussions">

        <form action="profile.php" method="POST">
          <div class="discussion search">
            <div class="searchbar">
              <button class="searchButton" type="submit">
                <i class="fa fa-search" aria-hidden="true"> </i>
              </button>
              <input type="text" name="username" placeholder="Search..." required></input>
            </div>
          </div>
        </form>

        <?php
        $chat_count = count($chats);
        for ($i = 0; $i < $chat_count; $i++) {
          echo "
          <div class='discussion' onclick=\"getData('{$chats[$i]['username']}')\">
          <div class='photo' style='background-image: url({$chats[$i]['image']});'>
            <div class='online' style='background-color: {$chats[$i]['color']};'></div>
          </div>
          <div class='desc-contact'>
            <p class='name'><b>{$chats[$i]['fullname']}</b></p>
            <p class='message'>{$chats[$i]['last_message']}</p>
          </div>
          <div class='timer'><b>{$chats[$i]['last_message_time']}</b></div>
        </div>
          ";
        }
        ?>


        <!-- <div class="discussion message-active">
          <div class="photo" style="background-image: url(https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1050&q=80);">
            <div class="online"></div>
          </div>
          <div class="desc-contact">
            <p class="name">Megan Leib</p>
            <p class="message">9 pm at the bar if possible 😳</p>
          </div>
          <div class="timer">12 sec</div>
        </div>

        <div class="discussion">
          <div class="photo" style="background-image: url(https://i.pinimg.com/originals/a9/26/52/a926525d966c9479c18d3b4f8e64b434.jpg);">
            <div class="online"></div>
          </div>
          <div class="desc-contact">
            <p class="name">Dave Corlew</p>
            <p class="message">Let's meet for a coffee or something today ?</p>
          </div>
          <div class="timer">3 min</div>
        </div>

        <div class="discussion">
          <div class="photo" style="background-image: url(https://images.unsplash.com/photo-1497551060073-4c5ab6435f12?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=667&q=80);">
          </div>
          <div class="desc-contact">
            <p class="name">Jerome Seiber</p>
            <p class="message">I've sent you the annual report</p>
          </div>
          <div class="timer">42 min</div>
        </div>

        <div class="discussion">
          <div class="photo" style="background-image: url(https://card.thomasdaubenton.com/img/photo.jpg);">
            <div class="online"></div>
          </div>
          <div class="desc-contact">
            <p class="name">Thomas Dbtn</p>
            <p class="message">See you tomorrow ! 🙂</p>
          </div>
          <div class="timer">2 hour</div>
        </div>

        <div class="discussion">
          <div class="photo" style="background-image: url(https://images.unsplash.com/photo-1553514029-1318c9127859?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=700&q=80);">
          </div>
          <div class="desc-contact">
            <p class="name">Elsie Amador</p>
            <p class="message">What the f**k is going on ?</p>
          </div>
          <div class="timer">1 day</div>
        </div>

        <div class="discussion">
          <div class="photo" style="background-image: url(https://images.unsplash.com/photo-1541747157478-3222166cf342?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=967&q=80);">
          </div>
          <div class="desc-contact">
            <p class="name">Billy Southard</p>
            <p class="message">Ahahah 😂</p>
          </div>
          <div class="timer">4 days</div>
        </div>

        <div class="discussion">
          <div class="photo" style="background-image: url(https://images.unsplash.com/photo-1435348773030-a1d74f568bc2?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1050&q=80);">
            <div class="online"></div>
          </div>
          <div class="desc-contact">
            <p class="name">Paul Walker</p>
            <p class="message">You can't see me</p>
          </div>
          <div class="timer">1 week</div>
        </div>

        <div class="discussion">
          <div class="photo" style="background-image: url(https://images.unsplash.com/photo-1435348773030-a1d74f568bc2?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1050&q=80);">
            <div class="online"></div>
          </div>
          <div class="desc-contact">
            <p class="name">Paul Walker</p>
            <p class="message">You can't see me</p>
          </div>
          <div class="timer">1 week</div>
        </div>
        <div class="discussion">
          <div class="photo" style="background-image: url(https://images.unsplash.com/photo-1435348773030-a1d74f568bc2?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1050&q=80);">
            <div class="online"></div>
          </div>
          <div class="desc-contact">
            <p class="name">Paul Walker</p>
            <p class="message">You can't see me</p>
          </div>
          <div class="timer">1 week</div>
        </div> -->
      </section>
      <section class="chat">
        <!-- <div class="header-chat">
          <i class="icon fa fa-user-o" aria-hidden="true"></i>
          <p class="name">Megan Leib</p>
          <i class="icon clickable fa fa-ellipsis-h right" aria-hidden="true"></i>
        </div>
        <div class="messages-chat">
          <div class="message">
            <div class="photo" style="background-image: url(https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1050&q=80);">
              <div class="online"></div>
            </div>
            <p class="text"> Hi, how are you ? </p>
          </div>
          <div class="message text-only">
            <p class="text"> What are you doing tonight ? Want to go take a drink ?</p>
          </div>
          <p class="time"> 14h58</p>
          <div class="message text-only">
            <div class="response">
              <p class="text"> Hey Megan ! It's been a while 😃</p>
            </div>
          </div>
          <div class="message text-only">
            <div class="response">
              <p class="text"> When can we meet ?</p>
            </div>
          </div>
          <p class="response-time time"> 15h04</p>
          <div class="message">
            <div class="photo" style="background-image: url(https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1050&q=80);">
              <div class="online"></div>
            </div>
            <p class="text"> 9 pm at the bar if possible 😳</p>
          </div>
          <p class="time"> 15h09</p>


          <div class="message text-only">
            <p class="text"> What are you doing tonight ? Want to go take a drink ?</p>
          </div>
          <div class="message text-only">
            <p class="text"> What are you doing tonight ? Want to go take a drink ?</p>
          </div>
          <div class="message text-only">
            <p class="text"> What are you doing tonight ? Want to go take a drink ?</p>
          </div>
          <div class="message text-only">
            <p class="text"> What are you doing tonight ? Want to go take a drink ?</p>
          </div>
          <p class="time"> 14h58</p>
        </div>
<form>
        <div class="footer-chat">
          <i class="icon fa fa-smile-o clickable" style="font-size:25pt;" aria-hidden="true"></i>
          <input type="text" class="write-message" placeholder="Type your message here"></input>
          <i class="icon send fa fa-paper-plane-o clickable" aria-hidden="true"></i>
      </div>
      </form> -->
      </section>
      <script>
        const section = document.querySelector('.chat');

        const getData = async (username) => {
          section.innerHTML = ""

          const form = new FormData()
          form.append("chat_username", username)

          const response = await fetch('messages.php', {
            method: "POST",
            // credentials: "include",
            body: form
          })

          const text = await response.text()

          section.innerHTML += text;
        }

        
        function transmitMessage(username) {
          const message = document.querySelector('.write-message');
          // console.log(message)
          const messageObj = {
            type: 1,
            username, // user that will receive message
            message: message.value
          }
          socket.send(JSON.stringify(messageObj));

          Date.prototype.today = function () { 
    return ((this.getDate() < 10)?"0":"") + this.getDate() +"-"+(((this.getMonth()+1) < 10)?"0":"") + (this.getMonth()+1) +"-"+ this.getFullYear();
}

// For the time now
Date.prototype.timeNow = function () {
     return ((this.getHours() < 10)?"0":"") + this.getHours() +":"+ ((this.getMinutes() < 10)?"0":"") + this.getMinutes() +":"+ ((this.getSeconds() < 10)?"0":"") + this.getSeconds();
}
          
          const currentdate = new Date(); 
const datetime = currentdate.today() + " " + currentdate.timeNow();

          const messages_chat = document.querySelector(".messages-chat");
          const text = "<div class='message text-only'> <div class='response'>" +
          "<p class='text'>" +
          message.value
          + "</p> </div> </div> <p class='response-time time'>" + datetime + "</p><br><br>";
          messages_chat.innerHTML+=text;  

          message.value = "";
        }

        socket.onmessage = function(e) {
          // alert(e.data);
          // console.log(e);
          const json_object = JSON.parse(e.data);
          const message = json_object.message;
          const from = json_object.from;
          const sent_date = json_object.date;

          const messages_chat = document.querySelector(".messages-chat");
          if(!messages_chat)
          {
            alert(from + " sent a message!");
          }
          else
          {
            const text = "<div class='message text-only'>" + 
          "<p class='text'>" + message + "</p> </div> " +
          "<p class='time'>" + sent_date + "</p>";
          messages_chat.innerHTML+=text;
          }
        }
      </script>
    </div>
  </div>
</body>

</html>