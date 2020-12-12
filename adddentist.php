<?php
    session_start();
    $db=mysqli_connect('localhost','root','','dcms') or die("could not connect to database");
    $msg = [];
    //echo $_SESSION['username'];
    if(!isset($_SESSION['username']))
    {
        $_SESSION['redirect'] = 'addclinic.php';
        header("location: login.php");
    }
    if($_SESSION['role'] == 'dentist' || $_SESSION['role'] == 'patient')
    header("location: index.php");
    if(isset($_POST['add_dentist']))
    {
        $uname = mysqli_real_escape_string($db, $_POST['username']);
        $name = mysqli_real_escape_string($db, $_POST['name']);
        $age = mysqli_real_escape_string($db, $_POST['age']);
        $s = mysqli_real_escape_string($db, $_POST['s']);
        $type = mysqli_real_escape_string($db, $_POST['dtype']);
        $mobile_no = $_POST['mobile_no'];
        $clinic = mysqli_real_escape_string($db, $_POST['clinic']);
        $noerr = 0;
        if($mobile_no < 700000000 || $mobile_no > 10000000000)
		{
            $noerr = 1;
            array_push($msg, "<h3 style='color:red'>Invalid mobile number</h3>");
        }
        if($age < 22 || $age > 60)
        {
            $noerr = 1;
            array_push($msg, "<h3 style='color:red'>Invalid age</h3>");
        }
		if($noerr == 0)
		{
            $mobile_no = mysqli_real_escape_string($db, $mobile_no);
            $query = "INSERT INTO dentist(username, name, phone, age, sex, d_type, location) VALUES ('$uname', '$name', '$mobile_no', '$age', '$s', '$type', '$clinic')"; 
            //echo $query;
            $res = mysqli_query($db, $query);
            if($res == true)
            {
                #echo "success";
                array_push($msg, "<h3 style='color:green'>Dentist information added successfully</h3>");
            }
            else
            array_push($msg, "<h3 style='color:red'>Unsuccessful, try again</h3>");
        }
    }
    #query to get dentists whose information is not there
    $query1 = "SELECT user.username FROM user WHERE role='dentist' AND username not in(select user.username from user, dentist where user.username=dentist.username)";
    $res1 = mysqli_query($db, $query1);
    $query2 = "SELECT location FROM clinic";
    $res2 = mysqli_query($db, $query2);
    if($res1 == false || mysqli_num_rows($res1) == 0)
    array_push($msg, "<h3 style='color:blue'>All dentists have their information added in the system</h3>");
    if($res2 == false || mysqli_num_rows($res2) == 0)
    array_push($msg, "<h3 style='color:red'>No clinics available</h3>");
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Add Dentist Information</title>
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
    <h3>Add Dentist Information</h3><br><br>
    <?php
    if(sizeof($msg)>0)
    {
        foreach($msg as $m)
        {
            echo $m;
        }
    }
    if($res1 && $res2 && mysqli_num_rows($res1) > 0 && mysqli_num_rows($res2) > 0)
    {
        echo "<form action = '' method='post'>";
        echo "<table><tr><th>Select dentist username</th><td><input list='uname' name='username' required><datalist id='uname'>";
        while($row = mysqli_fetch_assoc($res1))
        {
            $u1 = $row['username'];
            echo "<option value='$u1'>";
        }
        echo "</datalist></td></tr>"
    ?>
    
        <tr><th>Name</th><td><input type='text' id='name' name='name' maxlength="50" placeholder='John Doe' required></td></tr>
        <tr><th>Age</th><td><input type="text" name="age" maxlength="3"required></td></tr> 
        <tr><th>Mobile Number</th><td><input type="text" name="mobile_no" required></td></tr>    
        <tr><th>Sex</th><td><input list='s' name='s' required>
        <datalist id='s'><option value='F'><option value='M'></datalist></td></tr>
        <tr><th>Dentist Type</th><td><input list='types' name='dtype' required>
        <datalist id='types'><option value='General'><option value='Orthodontist'></datalist></td></tr>
        <tr><th>Clinic</th><td><input list='clinic' name='clinic' required><datalist id='clinic'>
        <?php
            while($row = mysqli_fetch_assoc($res2))
            {
                $l1 = $row['location'];
                echo "<option value='$l1'>";
            }
        ?>
        </datalist></td></tr>
        </table>
        <br><input type='submit' name='add_dentist' value='Add Dentist' class='example_e' style='width:50%'></form>
    </div>
    <?php }?>
    </center>
</body>
</html>