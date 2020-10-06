<?php

session_start();

/*if(isset($_SESSION['username'])){

    $_SESSION['msg']= "You must Log in first to view this page";
    header("location: login.php");

}*/

if(isset($_GET['logout'])){
    
    session_destroy();
    unset($_SESSION['username']);
    header("location: login.php");

}

?>
<!DOCTYPE HTML>
<html>
<head>
       <title>Homepage</title>
</head>
<link rel="stylesheet" href="style.css">
<body>
    <header style="background-color:white">
    <center><h1>Dental Clinic Management System</h1></center>
    </header>
    <div class="menu">
    <a href="index.php">Home</a>
    <a href="login.php">Login</a>
    <a href="registration.php">Sign Up</a>
    </div>
    <center>
    <?php if(isset($_SESSION['success'])):?>
        <div>
            <h3>
            <?php
            echo $_SESSION['success'];
            unset($_SESSION['success'])
            ?>
            </h3>
        </div>
    <?php endif; ?>
    <?php if(isset($_SESSION['username'])):?>
    <h3>Welcome <strong><?php echo $_SESSION['username'] ; ?> </strong>
    <button class="button"><a href="index.php?logout='1'">Logout</a></button>
    </h3>
    <?php endif; ?>
    <div class="content-section" style="width:50%">
    <h3>Site Under Construction!</h3>
    </div>
</center>
</body>
</html>