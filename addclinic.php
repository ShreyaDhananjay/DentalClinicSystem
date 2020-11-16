<?php
    session_start();
    $db=mysqli_connect('localhost','root','','dcms') or die("could not connect to database");
    $msg = [];
    if(!isset($_SESSION['username']))
    {
        $_SESSION['redirect'] = 'addclinic.php';
        header("location: login.php");
    }
    if($_SESSION['role'] == 'dentist' || $_SESSION['role'] == 'patient')
    header("location: index.php");
    if(isset($_POST['add_clinic']))
    {
        $locn = mysqli_real_escape_string($db, $_POST['location']);
        $t1 = substr($_POST['open_hr'], 0, 2)."00";
        $t2 = substr($_POST['close_hr'], 0, 2)."00";
        if($t2 > $t1)
        {
            $open_hr = mysqli_real_escape_string($db, $t1);
            $close_hr = mysqli_real_escape_string($db, $t2);
            $query = "INSERT INTO clinic(location, open_hr, close_hr) VALUES ('$locn', '$open_hr', '$close_hr')"; 
            //echo $query;
            $res = mysqli_query($db, $query);
            if($res == true)
            {
                #echo "success";
                array_push($msg, "Clinic added successfullly");
            }
            else
            array_push($msg, "Unsuccessful, try again");
        }
        else
        {
            array_push($msg, "Closing hour should be after opening hour");
        }
    }
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Add Clinic</title>
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
  <div class="content-section" style="width:70%">
    <h3>Add Clinic</h3><br><br>
    <?php
    if(sizeof($msg)>0)
    {
        foreach($msg as $m)
        {
            if($m[2] == 'i')
            echo "<h3 style='color:green; width:75%'>".$m."</h3><br>";
            else
            echo "<h3 style='color:red; width:75%'>".$m."</h3><br>";
        }
    }
    ?>
    <form action = '' method='post'>
    <table>
        <tr><th>Location</th><td><input type='text' id='locn' name='location' maxlength="32"placeholder='e.g. Bandra' required></td></tr>
        <tr><th>Opening Hour</th><td><input list='times' name='open_hr' required><datalist id='times'><option value='10:00'><option value='11:00'>
        <option value='12:00'><option value='13:00'><option value='14:00'><option value='15:00'>
        <option value='16:00'><option value='17:00'></datalist></td></tr>
        <tr><th>Closing Hour</th><td><input list='times2' name='close_hr' required>
        <datalist id='times2'>
        <option value='11:00'><option value='12:00'><option value='13:00'><option value='14:00'><option value='15:00'>
        <option value='16:00'><option value='17:00'></datalist></td></tr>
        </table>
        <br><input type='submit' name='add_clinic' value='Add Clinic' class='example_e' style='width:50%'></form>
    </div>
    </center>
</body>
</html>