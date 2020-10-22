<?php
session_start();
$db = mysqli_connect('localhost','root','','dcms') or die("could not connect to database");
if(isset($_SESSION['username']))
header('location:index.php');
//echo $_SESSION['redirect'];
?>
<html>
<head>
  <title>Log in</title>
  <link rel="stylesheet" href="style.css">
  <style>
  label{
    font-weight: bold;
  }
  </style>
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
    </div>
    <br><br><br>
    <div class="content-section" style="width:50%">
      <h3>Log In</h3><br>
      <form action="" method="post">
          <label for="username">Username</label>
          <input type="text" name="username" required>
          <label for="password">Password</label>
          <input type="password" name="password" required>
          <label>Select the type of user</label><br>
          <input type="radio" name="role" id="dentist" value="dentist" required style="width:10%">
          <label for="dentist" style="font-weight:normal">Dentist</label><br>
          <input type="radio" name="role" id="patient" value="patient" required style="width:10%">
          <label for="patient" style="font-weight:normal">Patient</label><br>
        <input type="submit" name="login_user" class="button" value="Log In" style="color:royalblue"/>
      </form>
      <p>Not a user yet? <a href="registration.php" style="color:black">Register</a></p>  
  </div>
  <?php  
      $username="";
      $count1=0;
       if(isset($_POST['login_user'])){
         echo "inside first if";
        if(!isset($_SESSION['username']))
        {
          $username = mysqli_real_escape_string($db, $_POST ['username']);
          $password = mysqli_real_escape_string($db, $_POST['password']);
          $role = mysqli_real_escape_string($db, $_POST['role']);
          echo "inside second if";
          if(!empty($username) && !empty($password))
          {
            $password= hash('sha256',$password);
            $query = " SELECT * FROM user WHERE username='$username' and password='$password' and role = '$role' LIMIT 1";
            $results = mysqli_query($db, $query);
            $res = mysqli_fetch_assoc($results);
            if($username == $res['username'] && $password==$res['password'] && $role == $res['role'])
            {
                echo "inside if";
                $_SESSION['username']=$username;
                $_SESSION['success'] = "Logged in succesfully";
                $_SESSION['role'] = $role;
                if(isset($_SESSION['redirect']))
                {
                    echo "inside redirect";
                    $link = $_SESSION['redirect'];
                    unset($_SESSION['redirect']);
                    echo "location:$link";
                    header("location:$link");
                }
                heade('location:index.php');
            }
            else{
              echo "<h3 style='color:red; width:50%'>Incorrect password/username/role</h3><br>";
            }
          }
        }
      }
    ?>
  </center>
</body>
</html>