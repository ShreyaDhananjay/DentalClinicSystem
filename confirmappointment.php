<?php
    #for dentists only
    session_start();
    $db=mysqli_connect('localhost','root','','dcms') or die("could not connect to database");
    if(!isset($_SESSION['username']))
    {
        $_SESSION['redirect'] = 'confirmappointment.php';
        header("location: login.php");
    }
    if($_SESSION['role']== 'patient')
    header("location: index.php");
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Appointment Status</title>
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
    <div class="content-section" style="width:85%">
    <h3>Confirm/Reject Appointments</h3><br><br>
    <?php
        $query = "SELECT * FROM appointment WHERE dname='".$_SESSION['username']."'";
        $result = mysqli_query($db, $query);
        if($result == false || mysqli_num_rows($result) == 0)
        echo "<h4>No appointments to show</h4>";
        else
        {
            echo "<h4>Pending Appointments</h4>";
            echo "<table><tr><th>Patient Name</th><th>Date</th><th>Time</th><th>Purpose</th><th></th><th></th></tr>";
            while($row = mysqli_fetch_assoc($result))
            {
                if(date('Y-m-d') < $row['date'])
                {
                    $url='#';
                    #$url = "appointments.php?delete=".$row['appt_id'];
                    echo "<tr><td>".$row['uname']."</td><td>".$row['date']."</td>";
                    echo "<td>".$row['time']."HRS</td><td>".$row['reason']."</td>";
                    echo "<td><a href='".$url."' style='color:green'>Confirm Appointment</a>";
                    echo "<br><br><form action='' method='post'><a href='".$url."' style='color:red'>Reject Appointment</a></td></tr>";
                }

            }
        }
    ?>
    </div>
</body>
</html>