<?php
    session_start();
    $db=mysqli_connect('localhost','root','','dcms') or die("could not connect to database");
    if(!isset($_GET['location']))
    header("location: clinics.php");
    $locn = $_GET['location'];
    if(!isset($_SESSION['username']))
    {
        $_SESSION['redirect'] = "dentist.php?location=".$locn;
        header("location: login.php");
    }
    if($_SESSION['role'] == 'dentist')
    header("location: index.php");
    
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Dentists</title>
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
                    $link = "makeappointment.php?dentist=".$row['username']."";
                    echo "<tr><td>Dr. ".$row['name']."</td><td>".$row['d_type']."</td><td><a href='$link'>Make Appointment</a></td></tr>";
                }
            }
        }
        ?>
        </table>
    </div>
    </center>
</body>
</html>