<?php
session_start();
$db=mysqli_connect('localhost','root','','dcms') or die("could not connect to database");
if(isset($_GET['logout'])){
    
    session_destroy();
    unset($_SESSION['username']);
    unset($_SESSION['role']);
    //unset($_COOKIE['remember']);
    header("location: login.php");
}

?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Home</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:500&display=swap" rel="stylesheet">
</head>
	
	<body>
    <?php require_once("header.php");?>
        <div id="mobile__menu" class="overlay">
            <a class="close">&times;</a>
            <div class="overlay__content">
                <a href="index.php">Home</a>
                <a href="registration.php">Sign up</a>
                <a href="login.php">Login</a>
            </div>
        </div>
		<div class=homemain>
		<div class="caption">
		<?php if(isset($_SESSION['username'])):?>
	
    <h2>Welcome <strong><?php echo $_SESSION['username'] ; ?> </strong>
    </h2>
    <?php endif; ?>
	<br>
	<br>
	<br>
		<a class="w">We prioritise your</a>
		<h1>NEW SMILE</h1>
        <!--<button class="ta" href="#">Read more</button>-->
		</div>
		</div>
		<?php if(isset($_SESSION['success'])):?>
        <div>
            <h3>
            <?php
            echo $_SESSION['success'];
            unset($_SESSION['success'])
            ?>
            </h3>
        </div>
    <?php endif;?>
    
		<div>
		<a class="main"><img src="images/main.jpg" alt="main"></a>
		</div>
        <script type="text/javascript" src="mobile.js"></script>  
</center>
</body>
</html>