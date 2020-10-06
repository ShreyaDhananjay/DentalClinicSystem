<html>
<head>
  <title>Log in</title>
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
    <br><br><br>
    <div class="content-section" style="width:50%">
      <h3>Log In</h3>
      <form action="" method="post">
          <label for="username">Username</label>
          <input type="text" name="username" required>
          <label for="password">Password</label>
          <input type="password" name="password" required>
        <input type="submit" name="login_user" class="button" value="Log In" style="color:royalblue"/>
      </form>
      <p>Not a user yet? <a href="registration.php" style="color:black">Register</a></p>  
  </div>
  <?php  
      session_start();
      $username="";
      $count1=0;
      $db=mysqli_connect('localhost','root','','dcms') or die("could not connect to database");
       if(isset($_POST['login_user'])){
        $username = mysqli_real_escape_string($db, $_POST ['username']);
        $password = mysqli_real_escape_string($db, $_POST['password']);
        if(!empty($username) && !empty($password)){
          $password= md5($password);
          $query = " SELECT * FROM user WHERE username='$username' and password='$password' LIMIT 1";
          //echo $query;
          $results = mysqli_query($db, $query);
          $res = mysqli_fetch_assoc($results);
          if($username == $res['username'] && $password==$res['password']){
              $_SESSION['username']=$username;
              $_SESSION['success'] = "Logged in succesfully";
              header('location:index.php');
          }
          else{
            echo "<h3 style='color:red'>Incorrect password/username</h3>";
          }
        }
      }
    ?>
  </center>
</body>
</html>