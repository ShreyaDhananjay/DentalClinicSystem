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
		if($mobile_no < 700000000 || $mobile_no > 1000000000)
		{
			$msg = "<h3 style='color:red'>Invalid mobile number</h3>";
		}
		else
		{
			$dob = $_POST['dob'];
			$query = "UPDATE useraccount SET name='$name', mobile_no = '$mobile_no', dob='$dob' WHERE username = '$uname'";
			//echo $query;
			$res = mysqli_query($db, $query);
			if($res)
			$msg = "<h3 style='color:green'>Data updated successfully</h3>";
		}
	}
?>
<!DOCTYPE HTML>
<html>
<head>
	<title>Update Account Details</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="styles.css">
	<link href="style10.css" type="text/css"rel="stylesheet"/>
	<link href="https://fonts.googleapis.com/css?family=Montserrat:500&display=swap" rel="stylesheet">
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
  <?php require_once("header.php");?>
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
			<input type="submit" name="update_acc" class="example_e" value="Update Account Details" style="color:royalblue; width:45%"/>
		</form>
	<?php
	}
	?>
	</div>
	</center>
</body>
</html>