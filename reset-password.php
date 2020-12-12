<?php
    session_start();
    $db=mysqli_connect('localhost','root','','dcms') or die("could not connect to database");
    $msg = [];
    $s = 0;
    if(isset($_GET['key']) && isset($_GET['email']) && isset($_GET['action']) && $_GET['action'] == 'reset' && (!isset($_POST['reset_pass'])))
    {
        $z = "SELECT * from password_reset_temp where email='".$_GET['email']."' and key1='".$_GET['key']."'";
        #echo $z;
        $query = mysqli_query($db, $z);
        $email1 = $_GET['email'];
        if($query == false){
            array_push($msg, "Invalid reset link");
        }
        else
        $s = 1;
    }
    if(isset($_POST["reset_pass"])){
        #echo "here2";
   $error="";
   $pass1 = mysqli_real_escape_string($db,$_POST["pass1"]);
   $pass2 = mysqli_real_escape_string($db,$_POST["pass2"]);
   $email = $_POST["email"];
   $curDate = date("Y-m-d H:i:s");
   if ($pass1==$pass2){
       #echo "here";
   $pass1 = hash('sha256',$pass1);
   $q1= "UPDATE user SET `password`='".$pass1."' WHERE `email`='".$email."'";
   #echo $q1;
   mysqli_query($db, $q1);
   $q2 = "DELETE FROM `password_reset_temp` WHERE `email`='".$email."'";
   #echo $q2;
   mysqli_query($db,$q2);
    
   array_push($msg, '<h3 style="color:green; width:75%">Congratulations! Your password has been updated successfully.<br><a href="login.php">
   Click here</a> to Login.</p></div><br />');
      } 
   }
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Reset Password</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="styles.css">
    <link href="style10.css" type="text/css"rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:500&display=swap" rel="stylesheet">
    <style>
        .menu a{
            color:white;
        }
        a{
            color:dodgerblue;
        }
        </style>
</head>
<body>
  <center>
  <?php require_once("header.php");?>
  <div class="content-section" style="width:70%">
    <h3>Reset your password</h3><br><br>
    <?php
    if(sizeof($msg)>0)
    {
        foreach($msg as $m)
        {
            echo $m;
            #echo "<h3 style='color:red; width:75%'>".$m."</h3><br>";
        }
    }
    if($s){
        $row = mysqli_fetch_assoc($query);
        $expDate = $row['expDate'];
        if ($expDate >= date("Y-m-d H:i:s")){   
    ?>
    <form action = '' method='post'>
    <table>
        <tr><th>Password</th><td><input type='password' name='pass1' maxlength="32" required></td></tr>
        <tr><th>Confirm Password</th><td><input type='password' name='pass2' maxlength="32" required></td></tr>
        <input type="hidden" name="email" value="<?php echo $email1;?>"/>
        </table>
        <br><input type='submit' name='reset_pass' value='Reset Password' class='example_e' style='width:50%'></form>
    </div>
    </center>
    <?
    
    }}?>
</body>
</html>