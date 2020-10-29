<?php
session_start();
$db=mysqli_connect('localhost','root','','dcms') or die("could not connect to database");
if(!isset($_SESSION['username']))
{
    $_SESSION['redirect'] = 'clinics.php';
    header("location: login.php");
}
?>
<!DOCTYPE HTML>
<html>
<head><title>Clinics</title>
<link href="style.css"type="text/css"rel="stylesheet"/> 
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
    <a href="index.php?logout='1'">Logout</a> 
<?php }?>
    </div>
<br><br>
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
                echo "<tr><td>".$id."</td><td>".$row['location']."</td><td>".$row['open_hr']."</td><td>".$row['close_hr']."</td><td><a href='".$link."' class='".$cl."'>Check Dentists</a></td></tr>";
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