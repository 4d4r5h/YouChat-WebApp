<?php

session_start();
if (isset($_SESSION['is_logged_in'])) {
    echo "<script> window.alert('You are already logged in.'); 
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
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="index.css">
</head>

<body>

    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="signup.php" method="POST">
                <h1>Create Account</h1>
                <!-- <div class="social-container">
				<a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
				<a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
				<a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
			</div>
			<span>Please enter all the details correctly.</span> -->
                <br>
                <input type="text" placeholder="Full Name" name="fullname" required/>
                <input type="text" placeholder="Username" name="username" required/>
                <input type="email" placeholder="Email Address" name="email" required/>
                <input type="password" placeholder="Password" name="password" required/>
                <br>
                <button>Sign Up</button>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form action="signin.php" method="POST">
                <h1>Sign In</h1>
                <!-- <div class="social-container">
				<a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
				<a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
				<a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
			</div>
			<span>or use your account</span> -->
                <br>
                <input type="text" placeholder="Username" name="username" required/>
                <input type="password" placeholder="Password" name="password" required/>
                <a href="#">Forgot your password?</a>
                <button>Sign In</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us please login with your personal info!</p>
                    <button class="ghost" id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hello, Friend!</h1>
                    <p>Enter your personal details and start journey with us!</p>
                    <button class="ghost" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>
            Created with <i class="fa fa-heart"></i> by
            <a target="_blank" rel="noreferrer" href="https://florin-pop.com">Adarsh Kumar</a>
            - Read how I created this and how you can join the challenge
            <a target="_blank" rel="noreferrer" href="https://www.florin-pop.com/blog/2019/03/double-slider-sign-in-up-form/">here</a>.
        </p>
    </footer>

    <script src="index.js"></script>
</body>

</html>