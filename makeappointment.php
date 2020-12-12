<?php
    session_start();
    $db=mysqli_connect('localhost','root','','dcms') or die("could not connect to database");
    $err = [];
    if(!isset($_SESSION['username']))
    header("location: login.php");
    if(!isset($_GET['dentist']))
    header("location: dentist.php");
    if($_SESSION['role'] == 'dentist')
    header("location: index.php");
    $dentist_uname = $_GET['dentist'];
    if(isset($_POST['make_appt']))
    {
        $notposs = 0;
        $uname = mysqli_real_escape_string($db, $_SESSION['username']);
        $dname = mysqli_real_escape_string($db, $dentist_uname);
        $locn = mysqli_real_escape_string($db, $_POST['clinic']);
        $date_val = mysqli_real_escape_string($db, $_POST['txtDate']);
        $t1 = substr($_POST['time'], 0, 2)."00";
        $hr = mysqli_real_escape_string($db, $t1);
        $reason = mysqli_real_escape_string($db, $_POST['reason']);
        $checkq = "SELECT * FROM appointment WHERE uname = '$uname' AND time='$hr' AND date='$date_val' AND (status='Pending' OR status='Confirmed')";
        $checkq2 = "SELECT * FROM appointment WHERE dname = '$dname' AND time='$hr' AND date='$date_val' AND status='Confirmed'";
        #echo $checkq2;
        $res = mysqli_query($db, $checkq);
        $res2 = mysqli_query($db, $checkq2);
        if($res != false && mysqli_num_rows($res) > 0)
        {
            $notposs = 1;
            array_push($err, "<h3 style='color:red'>You already have a pending or confirmed appointment at the same time.</h3>");
        }
        if($res2 != false && mysqli_num_rows($res2) > 0){
            $notposs = 1;
            array_push($err, "<h3 style='color:red'>The dentist already has a confirmed appointment at the same time</h3><h3 style='color:red'> Please choose a different date/time</h3>");
        }
        if($notposs == 0)
        {
            $query = "INSERT INTO appointment(dname, uname, location, date, time, reason) VALUES ('$dname', '$uname', '$locn', '$date_val', '$hr', '$reason')"; 
            if(mysqli_query($db, $query))
            header("location: appointments.php");
        }
    }
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Make Appointment</title>
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
        #dname, #clinic{
            background-color: powderblue;
        }
        </style>
</head>
<body>
  <center>
  <?php require_once("header.php");?>
    <div class="content-section" style="width:70%">
    <h3>Make Appointment</h3><br><br>
        <?php 
        if(sizeof($err)>0)
        {
            foreach($err as $m)
            {
                echo $m;
            }
        }
        $query = "SELECT * from dentist WHERE username='$dentist_uname'";
        $result = mysqli_query($db, $query);
        if($result == false || mysqli_num_rows($result) == 0)
        echo "<h3>Invalid Dentist Name</h3>";
        else
        {
            $row = mysqli_fetch_assoc($result);
            echo "<form action = '' method='post'>";
            $name = "<input type='text' name='dname' id='dname' value='Dr. ".$row['name']."' readonly/>";
            $clinic = "<input type='text' name='clinic' id='clinic' value='".$row['location']."' readonly/>";
            echo "<table><tr><th>Dentist Name</th><td>".$name."</td></tr>";
            echo "<tr><th>Clinic</th><td>".$clinic."</td></tr>";
            $d1 = date('Y-m-d');
            $dt = date('Y-m-d', strtotime($d1.' + 1 days'));
            echo "<tr><th>Choose Date</th><td><input type='date' name='txtDate' id='tdate' min='".$dt."'required></td></tr>";
            echo "<tr><th>Choose Time</th><td><input list='times' name='time' required><datalist id='times'><option value='10:00'><option value='11:00'>";
            echo "<option value='12:00'><option value='13:00'><option value='14:00'><option value='15:00'>";
            echo "<option value='16:00'><option value='17:00'></datalist></td></tr>";
            echo "<tr><th>Reason For Appointment</th><td><input type='text' id='reason' name='reason' value='Check-Up' required></td></tr>";
            echo "</table><input type='submit' name='make_appt' value='Make Appointment' class='example_e' style='width:50%'></form>";
        }
        ?>
        </table>
    </div>
    </center>
</body>
</html>