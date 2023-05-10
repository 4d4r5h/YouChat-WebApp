<?php
session_start();
if (!isset($_SESSION['is_logged_in'])) {
    echo "<script> window.alert('You are not logged in.'); 
    window.location='login.php'; </script>";
    exit();
}

include 'dbconnect.php';
include 'keep_online.php';

$sql = "SELECT * FROM users WHERE username=" . "'" . $_SESSION['user_logged_in'] . "'";
$result = mysqli_query($conn, $sql);
$about = "Hey there! I'm using YouChat.";
$username = "NULL";
$fullname = "NULL";
$date = "NULL";
$time = "NULL";
$email = "NULL";
$image = "NULL";
$hashed_password = "NULL";
while ($row = mysqli_fetch_assoc($result)) {
    if (isset($row["last_online"])) {
        $timestamp = strtotime($row["last_online"]);
        $date = date('d-m-Y', $timestamp);
        $time = date('h:i:s A', $timestamp);
    }
    $fullname = $row["fullname"];
    $username = $row["username"];
    $email = $row["email"];
    if (isset($row["about"]))
        $about = $row["about"];
    if (isset($row["profile_picture"]))
        $image = "data:image;base64," . $row["profile_picture"];
    $hashed_password = $row["password"];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "UPDATE users SET fullname = '$fullname', ";

    $email = $_POST["email"];
    if (!empty($email))
        $sql = $sql . "email = '$email', ";

    $fullname = $_POST["fullname"];
    if (!empty($fullname))
        $sql = $sql . "fullname = '$fullname', ";

    $about = $_POST["about"];
    if (!empty($about))
        $sql = $sql . "about = '$about', ";

    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    if (!(empty($current_password) or empty($new_password) or empty($confirm_password))) {
        if (password_verify($current_password, $hashed_password) and $new_password == $confirm_password) {
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = $sql . "password = '$new_hashed_password', ";
        } else {
            echo "<script> window.alert('Enter correct password.'); </script>";
        }
    }

    $sql = substr($sql, 0, -2);

    $sql = $sql . "WHERE username=" . "'" . $_SESSION['user_logged_in'] . "'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<script> window.alert('Profile updated successfully.'); 
    window.location='profile.php'; </script>";
        exit();
    } else {
        die("Error: " . $sql . "<br>" . mysqli_error($conn));
    }
}

// mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>YouChat</title>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" />
     <link rel="stylesheet" href="edit_profile.css"> 
    <link href="https://cdn.usebootstrap.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">

</head>



<body>
    <div class="container">
        <div class="row flex-lg-nowrap">
            <!-- <div class="col-12 col-lg-auto mb-3" style="width: 200px;">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="px-xl-3">
                            <button class="btn btn-block btn-secondary" onclick="window.location.href='profile.php'">
                                <i class="fa fa-arrow-circle-o-left"></i>
                                <span>Back</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- <div class="col-12 col-lg-auto mb-3" style="width: 200px;">
                <div class="card p-3">
                    <div class="e-navlist e-navlist--active-bg">
                        <ul class="nav">
                            <li class="nav-item"><a class="nav-link px-2 active" href="#"><i class="fa fa-fw fa-bar-chart mr-1"></i><span>Overview</span></a></li>
                            <li class="nav-item"><a class="nav-link px-2" href="https://www.bootdey.com/snippets/view/bs4-crud-users" target="__blank"><i class="fa fa-fw fa-th mr-1"></i><span>CRUD</span></a></li>
                            <li class="nav-item"><a class="nav-link px-2" href="https://www.bootdey.com/snippets/view/bs4-edit-profile-page" target="__blank"><i class="fa fa-fw fa-cog mr-1"></i><span>Settings</span></a></li>
                        </ul>
                    </div>
                </div>
            </div> -->


            <div class="col">
                <div class="row">
                    <div class="col mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="e-profile">
                                    
                                        <div class=header>    
                                            <div class="row">
                                                    <div class="col-12 col-sm-auto mb-3">
                                                        <div class="mx-auto" style="width: 140px;">
                                                            <div class="d-flex  rounded" >
                                                                <!-- <span style="color: rgb(166, 168, 170); font: bold 8pt Arial;">140x140</span> -->
                                                                <img class="round" height="140px" width="140px"  src='<?php echo $image ?>' />
                                                            </div>
                                                        </div>
                                                        <div class="mt-3">
                                                                <form action="edit_picture.php" method="POST" enctype="multipart/form-data">
                                                                    <!-- Select Image File to Upload: -->
                                                                    <input id="profile_picture" type="file" name="file" style="color:transparent; width:150.px;"/>
                                                                    <br>
                                                                    <!-- <input type="submit" name="submit" value="Upload"> -->
                                                                    <button id="Change_pic"class="btn btn-primary" type="submit">
                                                                        <i class="fa fa-fw fa-camera"></i>
                                                                        <span>Change Photo</span>
                                                                    </button>
                                                                </form>
                                                        </div>
                                                    </div>
                                                    <div class="col d-flex flex-column flex-sm-row justify-content-between mb-3">
                                                        <div class="text-center text-sm-left mb-2 mb-sm-0">
                                                        
                                                            <h4 class="pt-sm-2 pb-1 mb-0 text-nowrap"><b><?php echo $fullname ?></b></h4>
                                                            <p class="mb-0"><?php echo $username ?></p>
                                                            <div class="text-muted"><small>Last Online : <?php echo $date . " - " . $time ?></small></div>
                                                            
                                                            
                                                            
                                                        </div>
                                                        <!-- <div class="text-center text-sm-right">
                                                            <span class="badge badge-secondary">administrator</span>
                                                            <div class="text-muted"><small>Joined 09 Dec 2017</small></div>
                                                        </div> -->
                                                    </div>
                                            </div>
                                        </div>
                                                <!-- <ul class="nav nav-tabs">
                                                    <li class="nav-item"><a href="" class="active nav-link">Settings</a></li>
                                                </ul> -->
                                  
                                    <div class="tab-content pt-3">
                                        <div class="tab-pane active">
                                            <form class="form" action="edit_profile.php" method="POST">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="form-group">
                                                                    <label>Full Name</label>
                                                                    <input class="form-control" type="text" name="fullname" placeholder="<?php echo $fullname ?>">
                                                                </div>
                                                            </div>
                                                            <!-- <div class="col">
                                                                <div class="form-group">
                                                                    <label>Username</label>
                                                                    <input class="form-control" type="text" name="username" placeholder="<?php echo $username ?>">
                                                                </div>
                                                            </div> -->
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="form-group">
                                                                    <label>Email Address</label>
                                                                    <input class="form-control" type="email" name="email" placeholder="<?php echo $email ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col mb-3">
                                                                <div class="form-group">
                                                                    <label>About</label>
                                                                    <textarea class="form-control" rows="5" maxlength="255" name="about" placeholder="<?php echo $about ?>"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 col-sm-6 mb-3">
                                                        <div class="mb-2">
                                                            <h5><?php echo"Change Password"?></h5>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="form-group">
                                                                    <label>Current Password</label>
                                                                    <input class="form-control" type="password" name="current_password" placeholder="••••••">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="form-group">
                                                                    <label>New Password</label>
                                                                    <input class="form-control" type="password" name="new_password" placeholder="••••••">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="form-group">
                                                                    <label>Confirm <span class="d-none d-xl-inline">Password</span></label>
                                                                    <input class="form-control" type="password" name="confirm_password" placeholder="••••••">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="col-12 col-sm-5 offset-sm-1 mb-3">
                                                        <div class="mb-2"><b>Keeping in Touch</b></div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <label>Email Notifications</label>
                                                                <div class="custom-controls-stacked px-2">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" class="custom-control-input" id="notifications-blog" checked="">
                                                                        <label class="custom-control-label" for="notifications-blog">Blog posts</label>
                                                                    </div>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" class="custom-control-input" id="notifications-news" checked="">
                                                                        <label class="custom-control-label" for="notifications-news">Newsletter</label>
                                                                    </div>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" class="custom-control-input" id="notifications-offers" checked="">
                                                                        <label class="custom-control-label" for="notifications-offers">Personal Offers</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> -->
                                                </div>
                                                <div class="col d-flex justify-content-end">
                                                                <button class="btn btn-primary" type="submit">Save Changes</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                               
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-3 mb-3">
                        <div class="card mb-3">
                            <div id="logout"class="card-body">
                                <div class="px-xl-3">
                                    <button class="btn btn-block btn-secondary" onclick="window.location.href='logout.php'">
                                        <i class="fa fa-sign-out"></i>
                                        <span>Logout</span>
                                    </button>
                                </div>
                            </div>
                            <div id="Profile" class="card-body">
                                <div class="px-xl-3">
                                    <button class="btn btn-block btn-secondary" onclick="window.location.href='profile.php'">
                                        <i class="fa fa-arrow-circle-o-left"></i>
                                        <span>Back to Profile</span>
                                    </button>
                                </div>
                            </div>
                            <div id="Home"class="card-body">
                                <div class="px-xl-3">
                                    <button class="btn btn-block btn-secondary" onclick="window.location.href='home.php'">
                                        <i class="fa fa-arrow-circle-o-left"></i>
                                        <span>Back to Home</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="card mb-3">
                    <div class="card-body">
                        <div class="px-xl-3">
                            <button class="btn btn-block btn-secondary" onclick="window.location.href='profile.php'">
                                <i class="fa fa-arrow-circle-o-left"></i>
                                <span>Back</span>
                            </button>
                        </div>
                    </div>
                </div> -->
                        <!-- <div class="card">
                            <div class="card-body">
                                <h6 class="card-title font-weight-bold">Support</h6>
                                <p class="card-text">Get fast, free help from our friendly assistants.</p>
                                <button type="button" class="btn btn-primary">Contact Us</button>
                            </div>
                        </div> -->
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        var upload_picture = document.getElementById("profile_picture");

        upload_picture.onchange = function() {
            if (this.files[0].size > 16777200) {
                alert("File size should be less than 16 MB.");
                this.value = "";
            };
        };
    </script>

</body>

</html>
