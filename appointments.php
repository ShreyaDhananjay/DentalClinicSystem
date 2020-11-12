<?php
    session_start();
    $db=mysqli_connect('localhost','root','','dcms') or die("could not connect to database");
    if(!isset($_SESSION['username']))
    {
        $_SESSION['redirect'] = 'appointments.php';
        header("location: login.php");
    }
    if(isset($_GET['delete']) && $_SESSION['role'] == 'patient') #delete pending appointment
    {
        $id = $_GET['delete'];
        $cancel_query = "DELETE FROM appointment WHERE appt_id='".$id."'";
        mysqli_query($db, $cancel_query);
        unset($_GET['cancel']);
    }
    if(isset($_GET['cancel']))
    {
        $id = $_GET['cancel'];
        $cancel_query = "UPDATE appointment SET status = 'Cancelled' WHERE appt_id='".$id."'";
        mysqli_query($db, $cancel_query);
        unset($_GET['cancel']);
    }
    if(isset($_GET['confirm']))
    {
        if($_SESSION['role'] == 'dentist')
        {
            $id = $_GET['confirm'];
            $cancel_query = "UPDATE appointment SET status = 'Confirmed' WHERE appt_id='".$id."'";
            mysqli_query($db, $cancel_query);
            unset($_GET['confirm']);
        }
    }
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Appointments</title>
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
    <h3>Appointments</h3><br><br>
    <?php
        if($_SESSION['role'] == 'patient') {
            $query = "SELECT * FROM appointment WHERE uname='".$_SESSION['username']."'";
            $result = mysqli_query($db, $query);
            if($result == false || mysqli_num_rows($result) == 0)
            echo "<h4>No appointments to show</h4>";
            else
            {
                echo "<h4>Your Appointments</h4>";
                echo "<table><tr><th>Dentist</th><th>Location</th><th>Date</th><th>Time</th><th>Purpose</th><th>Status</th><th></th></tr>";
                while($row = mysqli_fetch_assoc($result))
                {
                    if(date('Y-m-d') < $row['date'])
                    {
                        $query2 = "SELECT name FROM dentist WHERE username = '".$row['dname']."' LIMIT 1";
                        $dentist_name = mysqli_query($db, $query2);
                        $row2 = mysqli_fetch_assoc($dentist_name);
                        if($row['status'] == 'Pending')
                        $url = "appointments.php?delete=".$row['appt_id'];
                        else
                        $url = "appointments.php?cancel=".$row['appt_id'];
                        echo "<tr><td>Dr. ".$row2['name']."</td><td>".$row['location']."</td><td>".$row['date']."</td>";
                        echo "<td>".$row['time']."HRS</td><td>".$row['reason']."</td><td>".$row['status']."</td>";
                        if($row['status'] == 'Pending' || $row['status'] == 'Confirmed')
                        echo "<td><a href='".$url."' style='color:red'>Cancel Appointment</a></td></tr>";
                        else
                        echo "</tr>";
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
                echo "<table><tr><th>Patient Name</th><th>Date</th><th>Time</th><th>Purpose</th><th>Status</th><th></th></tr>";
                while($row = mysqli_fetch_assoc($result))
                {
                    if(date('Y-m-d') < $row['date'])
                    {
                        echo "<tr><td>".$row['uname']."</td><td>".$row['date']."</td>";
                        echo "<td>".$row['time']."HRS</td><td>".$row['reason']."</td><td>".$row['status']."</td>";
                        if($row['status'] == 'Pending')
                        {
                            $url1 = 'appointments.php?confirm='.$row['appt_id'];
                            $url2 = 'appointments.php?reject='.$row['appt_id'];
                            echo "<td><a href='".$url1."' style='color:green'>Confirm Appointment</a>";
                            echo "<br><br><form action='' method='post'><a href='".$url2."' style='color:red'>Reject Appointment</a></td></tr>";
                        }
                        elseif($row['status'] == 'Confirmed')
                        {
                            $url='appointments.php?cancel='.$row['appt_id'];
                            echo "<td><form action='' method='post'><a href='".$url."' style='color:red'>Cancel Appointment</a></td></tr>";
                        }
                    }

                }
            }
        }
    ?>
    </div>
</body>
</html>