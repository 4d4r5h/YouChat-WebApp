<?php
echo "<script> var socket  = new WebSocket('ws://localhost:8080');
  setTimeout(function() {
    const userInfo = {
      type: 0,
      username: '" . $_SESSION["user_logged_in"] . "',
    }

    socket.send(JSON.stringify(userInfo));
}, 1000);
  </script>";


$sql = "UPDATE users SET last_online = CURRENT_TIMESTAMP WHERE username=" . "'" . $_SESSION['user_logged_in'] . "'";
$result = mysqli_query($conn, $sql);
if (!$result) {
  die("Error in updating time: " . mysqli_error($conn));
}

?>