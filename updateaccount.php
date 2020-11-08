<?php
	session_start();
	$db=mysqli_connect('localhost','root','','dcms') or die("could not connect to database");
	$msg = "";
	if(!isset($_SESSION['username']))
	{
		$_SESSION['redirect'] = 'updateaccount.php';
		header("location: login.php");
	}
	if($_SESSION['role'] == 'dentist')
		header("location: index.php");
	if(isset($_POST['update_acc']))
	{
		global $msg;
		$uname = $_SESSION['username'];
		$name = $_POST['name'];
		$mobile_no = $_POST['mobile_no'];
		$dob = $_POST['dob'];
		$query = "UPDATE useraccount SET name='$name', mobile_no = '$mobile_no', dob='$dob' WHERE username = '$uname'";
		//echo $query;
		$res = mysqli_query($db, $query);
		if($res)
		$msg = "<h3 style='color:green'>Data updated successfully</h3>";
	}
?>
<!DOCTYPE HTML>
<html>
<head><title>Update Account Details</title>
<link href="style.css"type="text/css"rel="stylesheet"/> 
<style>
    th, td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
  }
  th {
    background-color: dodgerblue;
    color: white;
  }
  tr:hover {background-color: #ddd;}
  .spllink{
      color:dodgerblue;
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
    <?php if(isset($_SESSION['username']) /*|| isset($_COOKIE['remember'])*/){
        if($_SESSION['role'] == 'patient')
        {
			echo "<a href='clinics.php'>Clinics</a>";
        }
    ?> 
    <a href="appointments.php">Appointments</a>
    <a href="pastappointments.php">Past Appointments</a>
	<a href='updateaccount.php'>Update Account</a>
    <a href="index.php?logout='1'">Logout</a> 
	<?php }?>
    </div><br><br>
	<?php
		if($msg != "")
		echo $msg;
	?>
    <div class="content-section" style="width:50%">
	<?php
		$query = "SELECT * FROM useraccount WHERE username = '".$_SESSION['username']."' LIMIT 1";
		$user_details = mysqli_query($db, $query);
		if($user_details == false)
		{
			echo "User Not Found!";
		}
		else
		{
			$user_row = mysqli_fetch_assoc($user_details);
			$uname = $user_row['username'];
			$name = $user_row['name'];
			if($name == NULL)
			{
				$name = "Your Name";
			}
			$mobile_no = $user_row['mobile_no'];
			if($mobile_no == NULL)
			{
				$mobile_no = "";
			}
			$dob = $user_row['dob'];
			if($dob == NULL)
			{
				$dob = "2000-01-01";
			}
		?>
		<h2>Update Details</h2><br>
		<form action="" method="post">
			<table>
			<tr><th><label for="username">Username</label></th>
			<td><input type="text" name="username" value="<?php echo $uname; ?>"readonly></td></tr>
			<tr><th><label for="name">Name</label></th>
			<td><input type="text" name="name" maxlength="50" value="<?php echo $name; ?>"required></td></tr>
			<tr><th><label for="mobile_no">Mobile Number</label></th>
			<td><input type="text" name="mobile_no" value = "<?php echo $mobile_no; ?>" required></td></tr>
			<tr><th><label for="dob">Date of Birth</label></th>
			<td><input type="date" name="dob" value="<?php echo $dob; ?>" required></td></tr>
			</table>
			<input type="submit" name="update_acc" class="button" value="Update Account Details" style="color:royalblue; width:45%"/>
		</form>
	<?php
	}
	?>
	</div>
	</center>
</body>
</html>