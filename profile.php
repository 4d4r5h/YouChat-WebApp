<?php

session_start();
if (!isset($_SESSION['is_logged_in'])) {
    echo "<script> window.alert('You are not logged in.'); 
    window.location='login.php'; </script>";
    exit();
}

include 'dbconnect.php';
include 'keep_online.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
$username = $_POST["username"];
}
else{
$username = $_SESSION["user_logged_in"];
}
$sql = "SELECT * FROM users WHERE username=" . "'" . $username . "'";
$result = mysqli_query($conn, $sql);
$about = "Hey there! I'm using YouChat.";
$image = "NULL";
$date = "NULL";
$time = "NULL";
$fullname = "NULL";
$username = "NULL";
$color="grey";

while ($row = mysqli_fetch_assoc($result)) {
    if (isset($row["last_online"])) {
        $timestamp = strtotime($row["last_online"]);
        $date = date('d-m-Y', $timestamp);
        $time = date('h:i:s A', $timestamp);
    }
    if(isset($row["connection_id"])) {
        $color="green";
    }
    $fullname = $row["fullname"];
    $username = $row["username"];
    if(isset($row["profile_picture"]))
        $image="data:image;base64," . $row["profile_picture"];
    if (isset($row["about"]))
        $about = $row["about"];
}

if($username == "NULL")
{
    echo "<script> window.alert('User searched for does not exist.'); 
    window.location='home.php'; </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>YouChat</title>
    <!-- <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" /> -->
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet"> -->

</head>

<body>
    <div class="card-container">
    <!-- <span class="pro">PRO</span> -->
    <q1> <i class="fa fa-user" aria-hidden="true"></i> <?php echo "Profile" ?> </q1><br> 
    <div class="pic-name">
    <img class="round" src="<?php echo $image ?>" alt="user" width="75" height="75" style="opacity: 1; border: 10 solid black;" />
	<div class="dat-name">
        
            <h3> <?php echo $fullname ?> <i class="fa-solid fa-circle fa-xs" style="color: <?php echo $color ?>;"></i>  </h3>

            <!--<h6><?php echo $username ?></h6> -->
	    <p><?php echo $date . " - " . $time ?></p>
    </div>
    </div>

        <div class="buttons">
            <form action="add_chat.php" method="POST" style="display: inline;">
            <button class="primary" type="submit" name="to_username" value="<?php echo $username; ?>" <?php if($username == $_SESSION["user_logged_in"]) echo "disabled"; ?> >
            <i class="fa fa-home" aria-hidden="true"></i>
            <?php echo "Message"?>        
            </button>
            </form>
            <button class="primary ghost" onclick="window.location.href='edit_profile.php'" <?php if($username != $_SESSION["user_logged_in"]) echo "disabled"; ?>>
            <i class="fa fa-edit" aria-hidden="true"></i>
            <?php echo "Edit Profile"?> 
            </button>
            </div>
        <div class="skills">
            <h6>ABOUT</h6>
            <ul>
                <li>
                    <!-- Hello, my name is Adarsh Kumar
I am a third year student doing Btech in computer science and enginering at iit patna.
My interest lies in doing competetive programming  and i have been doing it for the past 1 year. In my free time. I love watching movies in my free time. -->
                    <?php
                    echo $about;
                    ?>
                </li>
                <!-- <li>Front End Development</li>
			<li>HTML</li>
			<li>CSS</li>
			<li>JavaScript</li>
			<li>React</li>
			<li>Node</li> -->
            </ul>
        </div>
    </div>

    <!-- <footer>
	<p>
		Created with <i class="fa fa-heart"></i> by
		<a target="_blank" href="https://florin-pop.com">Florin Pop</a>
		- Read how I created this
		<a target="_blank" href="https://florin-pop.com/blog/2019/04/profile-card-design">here</a>
		- Design made by
		<a target="_blank" href="https://dribbble.com/shots/6276930-Profile-Card-UI-Design">Ildiesign</a>
	</p>
</footer> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script> -->

</body>

</html>
