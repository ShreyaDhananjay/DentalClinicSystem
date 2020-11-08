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
<head><title>Appointment Status</title>
<link href="style.css"type="text/css"rel="stylesheet"/> 
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
  <div class="menu"> 
    <h1>Dental Clinic Management System</h1> 
    <a href="index.php">Home</a>
    <?php if(!isset($_SESSION['username'])/*&& !isset($_COOKIE['remember'])*/){?> 
    <a href="login.php">Login</a>
    <a href="registration.php">Sign Up</a>
    <?php }?>
    <?php if(isset($_SESSION['username']) /*|| isset($_COOKIE['remember'])*/){?> 
    <a href="clinics.php">Clinics</a>
    <a href="appointments.php">Appointments</a>
    <a href="pastappointments.php">Past Appointments</a>
    <a href="index.php?logout='1'">Logout</a> 
<?php }?>
    </div><br><br>
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