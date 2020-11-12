<?php
session_start();
$db=mysqli_connect('localhost','root','','dcms') or die("could not connect to database");
if(!isset($_SESSION['username']))
{
    $_SESSION['redirect'] = 'clinics.php';
    header("location: login.php");
}
if($_SESSION['role'] == 'dentist')
    header("location: index.php");
?>
<!DOCTYPE HTML>
<html>
    <head><title>Clinics</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="styles.css">
    <link href="style10.css"type="text/css"rel="stylesheet"/>
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
    <div class="content-section" style="width:75%">
        <h2>Clinics</h2>
        <?php 
        $query = "SELECT * FROM clinic";
        $res = mysqli_query($db, $query);
        if(mysqli_num_rows($res) > 0)
        {
            echo "<table><tr><th>ID</th><th>Location</th><th>Opening Hour</th><th>Closing Hour</th><th></th></tr>";
            while($row = mysqli_fetch_assoc($res))
            {
                $id = $row['clinic_id'];
                $link = "dentists.php?location=".$id."";
                $cl ="spllink";
                echo "<tr><td>".$id."</td><td>".$row['location']."</td><td>".$row['open_hr']."</td><td>".$row['close_hr']."</td><td><a class='nav__links' href='".$link."' class='".$cl."'>Check Dentists</a></td></tr>";
            }
        }
        else
        echo "<h3>No clinics available</h3>";
        ?>
        </table>
    </div>
    </center>
</body>
</html>