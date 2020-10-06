
<!DOCTYPE html>
<head>
  <title>Registration</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<center>
    <div class="menu">
    <h1>Dental Clinic Management System</h1>
    <a href="index.php">Home</a>
    <a href="login.php">Login</a>
    <a href="registration.php">Sign Up</a>
    </div>
    <br>
    <div class="content-section" style="width:50%">
      <h2>Registration</h2>
      <form action="" method="post">
        <div>
          <label for="username">Username</label>
          <input type="text" name="username" required>
        </div>
        <div>
          <label for="email">Email</label>
          <input type="email" name="email" required> 
        </div>
        <div>
          <label for="password">Password</label>
          <input type="password" name="password_1" required>
        </div>
        <div>
          <label for="password">Confirm password</label>
          <input type="password" name="password_2" required>
        </div>
        <input type="submit" name="reg_user" class="button" value="Create Account" style="color:royalblue"/>
      </form>
      <p>Already a user? <a href="login.php" style="color:black"><b>Log In</b></a></p>
    </div>
    <?php  
      session_start();
      $username="";
      $email="";
      $db=mysqli_connect('localhost','root','','dcms') or die("could not connect to database");
      if(isset($_POST['reg_user'])){
        $username= mysqli_real_escape_string($db, $_POST['username']);
        $email= mysqli_real_escape_string($db, $_POST['email']);
        $password_1= mysqli_real_escape_string($db, $_POST['password_1']);
        $password_2= mysqli_real_escape_string($db, $_POST['password_2']);
        $count1 = 0;
        if($password_1 !=$password_2)
        {
          $count = $count1+1;
          echo "<h3 style='color:red'>Passwords do not match</h3>";
        }
        $user_check_query= " SELECT * FROM user WHERE username='$username' or email='$email' LIMIT  1 ";
        $results= mysqli_query($db, $user_check_query);
        $user = mysqli_fetch_assoc($results);
        if($user){
          $count = $count1+1;
          if($user['username'] == $username)
            {
              echo "<h3 style='color:red'>Username already exists</h3>";
            }
          if($user['email'] == $email)
            {
              echo "<h3 style='color:red'>User with this email ID already exists</h3>";
            }
        }
        if($count1 == 0){
          $password = md5($password_1);
          $query = "INSERT INTO user (username, email, password) VALUES('$username', '$email', '$password')";
          mysqli_query($db, $query);
          header('location: login.php');
        }
      }
    ?>  
  </center>

</body>
</html>