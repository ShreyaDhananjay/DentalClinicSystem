<?php
    session_start();
    $db=mysqli_connect('localhost','root','','dcms') or die("could not connect to database");
    if(!isset($_SESSION['username']))
    {
        $_SESSION['redirect'] = 'pastappointments.php';
        header("location: login.php");
    }
?>
<!DOCTYPE HTML>
<html>
<head><title>Past Appointments</title>
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
    <div class="content-section" style="width:85%">
    <h3>Past Appointments</h3><br><br>
    <?php
        if($_SESSION['role'] == 'patient') {
            $query = "SELECT * FROM appointment WHERE uname='".$_SESSION['username']."'";
            $result = mysqli_query($db, $query);
            if($result == false || mysqli_num_rows($result) == 0)
            echo "<h4>No appointments to show</h4>";
            else
            {
                echo "<h4>Your Appointments</h4>";
                echo "<table><tr><th>Dentist</th><th>Location</th><th>Date</th><th>Time</th><th>Purpose</th><th>Status</th></tr>";
                while($row = mysqli_fetch_assoc($result))
                {
                    if(date('Y-m-d') > $row['date'])
                    {
                        $query2 = "SELECT name FROM dentist WHERE username = '".$row['dname']."' LIMIT 1";
                        $dentist_name = mysqli_query($db, $query2);
                        $row2 = mysqli_fetch_assoc($dentist_name);
                        echo "<tr><td>Dr. ".$row2['name']."</td><td>".$row['location']."</td><td>".$row['date']."</td>";
                        echo "<td>".$row['time']."HRS</td><td>".$row['reason']."</td><td>".$row['status']."</td></tr>";
                    }

                }
            }
        }
        elseif ($_SESSION['role'] == 'dentist') {
            $query = "SELECT * FROM appointment WHERE dname='".$_SESSION['username']."'";
            $result = mysqli_query($db, $query);
            if($result == false || mysqli_num_rows($result) == 0)
            echo "<h4>No appointments to show</h4>";
            else
            {
                echo "<h4>Your Appointments</h4>";
                echo "<table><tr><th>Patient Name</th><th>Date</th><th>Time</th><th>Purpose</th><th>Status</th></tr>";
                while($row = mysqli_fetch_assoc($result))
                {
                    if(date('Y-m-d') > $row['date'])
                    {
                        echo "<tr><td>".$row['uname']."</td><td>".$row['date']."</td>";
                        echo "<td>".$row['time']."HRS</td><td>".$row['reason']."</td><td>".$row['status']."</td></tr>";
                    }

                }
            }
        }
    ?>
    </div>
</body>
</html>