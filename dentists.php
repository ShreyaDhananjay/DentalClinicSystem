<?php
    session_start();
    $db=mysqli_connect('localhost','root','','dcms') or die("could not connect to database");
    if(!isset($_SESSION['username']))
    header("location: login.php");
    if(!isset($_GET['location']))
    header("location: clinics.php");
    if($_SESSION['role'] == 'dentist')
    header("location: index.php");
    $locn = $_GET['location'];
?>
<!DOCTYPE HTML>
<html>
<head><title>Dentists</title>
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
    <a href="index.php?logout='1'">Logout</a> 
<?php }?>
    </div><br><br>
    <div class="content-section" style="width:75%">
        <?php 
        $query2 = "SELECT location FROM clinic WHERE clinic_id = '$locn' LIMIT 1";
        $res_locn = mysqli_query($db, $query2);
        //var_dump($res_locn);
        if($res_locn == false || mysqli_num_rows($res_locn) == 0)
        echo "<h3>Invalid Location</h3>";
        else 
        {
            $row = mysqli_fetch_assoc($res_locn);
            $loc = $row['location'];
            echo "<h2>Dentists at $loc</h2><br>";
            $query = "SELECT * FROM dentist WHERE location = '$loc'";
            //echo $query;
            $res_dentist = mysqli_query($db, $query);
            if($res_dentist == false || mysqli_num_rows($res_dentist) == 0)
            echo  "<h3>No dentists available</h3>";
            else
            {
                echo "<table><tr><th>Name</th><th>Type</th><th></th></tr>";
                while($row = mysqli_fetch_assoc($res_dentist))
                {
                    echo "<tr><td>Dr. ".$row['name']."</td><td>".$row['d_type']."</td><td><a href='#'>Make Appointment</a></td></tr>";
                }
            }
        }
        ?>
        </table>
    </div>
    </center>
</body>
</html>