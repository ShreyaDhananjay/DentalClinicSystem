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
<head><title>Homepage</title>
<link href="style.css"type="text/css"rel="stylesheet"/> 
</head>
<body>
  <center>
  <div class="menu"> 
    <h1>Dental Clinic Management System</h1> 
    <a href="index.php">Home</a>
    <?php if(!isset($_SESSION['username'])/*&& !isset($_COOKIE['remember'])*/){?> 
    <a href="login.php">Login</a>
    <a href="registration.php">Sign Up</a>
    <?php }?>
    <?php if(isset($_SESSION['username']) /*|| isset($_COOKIE['remember'])*/){?> 
    <a href="clinics.php">Clinics</a>
    <a href="index.php?logout='1'">Logout</a> 
<?php }?>
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
    <?php if(isset($_SESSION['username'])):?>
    <h3>Welcome <strong><?php echo $_SESSION['username'] ; ?> </strong>
    </h3>
    <?php endif; ?>
    <div class="content-section" style="width:50%">
    <h3>Site Under Construction!</h3>
    </div>
</center>
</body>
</html>