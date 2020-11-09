<?php
    session_start();
    $db=mysqli_connect('localhost','root','','dcms') or die("could not connect to database");
    if(!isset($_SESSION['username']))
    header("location: login.php");
    if(!isset($_GET['dentist']))
    header("location: dentist.php");
    if($_SESSION['role'] == 'dentist')
    header("location: index.php");
    $dentist_uname = $_GET['dentist'];
    if(isset($_POST['make_appt']))
    {
        $uname = mysqli_real_escape_string($db, $_SESSION['username']);
        $dname = mysqli_real_escape_string($db, $dentist_uname);
        $locn = mysqli_real_escape_string($db, $_POST['clinic']);
        $date_val = mysqli_real_escape_string($db, $_POST['txtDate']);
        $t1 = substr($_POST['time'], 0, 2)."00";
        $hr = mysqli_real_escape_string($db, $t1);
        $reason = mysqli_real_escape_string($db, $_POST['reason']);
        #echo $hr;
        $query = "INSERT INTO appointment(dname, uname, location, date, time, reason) VALUES ('$dname', '$uname', '$locn', '$date_val', '$hr', '$reason')"; 
        mysqli_query($db, $query);
        #echo "success";
        header("location: appointments.php");
    }
?>
<!DOCTYPE HTML>
<html>
<head><title>Make Appointment</title>
<link href="style.css"type="text/css"rel="stylesheet"/> 
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
    <a href='updateaccount.php'>Update Account</a>
    <a href="index.php?logout='1'">Logout</a> 
<?php }?>
    </div><br><br>
    <div class="content-section" style="width:75%">
    <h3>Make Appointment</h3><br><br>
        <?php 
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
            echo "</table><input type='submit' name='make_appt' value='Make Appointment' class='button' style='width:50%'></form>";
        }
        ?>
        </table>
    </div>
    </center>
</body>
</html>