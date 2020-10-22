<?php  
      session_start();
      if(isset($_SESSION['username']))
      header('location:index.php');
      $username="";
      $email="";
      $errors=[];
      $db=mysqli_connect('localhost','root','','dcms') or die("could not connect to database");
      if(isset($_POST['reg_user']))
      {
        $username= mysqli_real_escape_string($db, $_POST['username']);
        $email= mysqli_real_escape_string($db, $_POST['email']);
        $password_1= mysqli_real_escape_string($db, $_POST['password_1']);
        $password_2= mysqli_real_escape_string($db, $_POST['password_2']);
        $role = mysqli_real_escape_string($db, $_POST['role']);
        //regex validation 
        $uppercase = preg_match('@[A-Z]@', $password_1);
        $lowercase = preg_match('@[a-z]@', $password_1);
        $number = preg_match('@[0-9]@', $password_1);
        $specialChars = preg_match('@[^\w]@', $password_1);
        if($password_1 !=$password_2) {
          array_push($errors, "Passwords do not match");
        }
        else if(!$uppercase || !$lowercase || !$number || !$number) {
          array_push($errors, "Password must contain at least one lowercase, one uppercase, one digit and one special character.");
        }
        else
        {
          $user_check_query= " SELECT * FROM user WHERE username='$username' or email='$email' LIMIT  1 ";
          $results= mysqli_query($db, $user_check_query);
          $user = mysqli_fetch_assoc($results);
          if($user)
          {
            if($user['username'] == $username) {
                array_push($errors, "Username already exists");
              }
            if($user['email'] == $email) {
                array_push($errors, "User with this email ID already exists");
              }
          }
          else{
            $password= hash('sha256',$password_1);
            $query = "INSERT INTO user (username, email, password, role) VALUES('$username', '$email', '$password', '$role')";
            mysqli_query($db, $query);
            header('location: login.php');
          }
        }
      }
    ?>  
<!DOCTYPE html>
<head>
  <title>Registration</title>
  <link rel="stylesheet" href="style.css">
  <style>
    input {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 8px;
    box-sizing: border-box;
    border: 1px solid powderblue;
    border-radius: 4px;
    text-align: center;
  }
  input:focus {
    border: 3px solid royalblue;
  }
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
    <br>
    <?php
      if(sizeof($errors)>0)
      {
        foreach($errors as $err)
        {
          echo "<h3 style='color:red; width:75%'>".$err."</h3><br>";
        }
      }
      ?>
    <div class="content-section" style="width:50%">
      <h2>Registration</h2><br>
      <form action="" method="post">
        <div>
          <label for="username">Username</label>
          <input type="text" name="username" minlength="2" maxlength="32" required>
        </div>
        <div>
          <label for="email">Email</label>
          <input type="email" name="email" maxlength="50" required> 
        </div>
        <div>
          <label for="password">Password</label><br>
          <p>Must contain at least one lowercase, one uppercase,</p>
          <p>one digit and one special character.</p>
          <input type="password" name="password_1" minlength="8" maxlength="32" required>
        </div>
        <div>
          <label for="password">Confirm password</label>
          <input type="password" name="password_2" minlength="8" maxlength="32" required>
        </div>
        <label>Select the type of user</label><br>
          <input type="radio" name="role" id="dentist" value="dentist" required style="width:10%">
          <label for="dentist" style="font-weight:normal">Dentist</label><br>
          <input type="radio" name="role" id="patient" value="patient" required style="width:10%">
          <label for="patient" style="font-weight:normal">Patient</label><br>
        <input type="submit" name="reg_user" class="button" value="Create Account" style="color:royalblue"/>
      </form>
      <p>Already a user? <a href="login.php" style="color:black"><b>Log In</b></a></p>
    </div>
    
  </center>

</body>
</html>