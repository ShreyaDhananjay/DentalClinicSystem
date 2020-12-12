<?php
    session_start();
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;
    require( 'PHPMailer.php' );
    require("Exception.php");
    require("SMTP.php");
    $db=mysqli_connect('localhost','root','','dcms') or die("could not connect to database");
    $msg = [];
    $err = 0;
    $allok = 0;
    error_reporting(E_ALL ^ E_WARNING);
    if(isset($_POST['forgot_pass']))
    {
        $email = $_POST["email"];
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!$email) {
            array_push($msg, "Invalid email address please type a valid email address!");
            $err++;
        }
        else{
            $sel_query = "SELECT * FROM user WHERE email='".$email."'";
            $results = mysqli_query($db,$sel_query);
            $row = mysqli_num_rows($results);
            if ($row==""){
                array_push($msg, "No user is registered with this email address!");
                $err++;
            }
            if($err == 0){
                $expFormat = mktime( date("H"), date("i"), date("s"), date("m") ,date("d")+1, date("Y"));
                $expDate = date("Y-m-d H:i:s",$expFormat);
                $key = md5(2418*2+$email);
                $addKey = substr(md5(uniqid(rand(),1)),3,10);
                $key = $key . $addKey;
                mysqli_query($db, "INSERT INTO `password_reset_temp` (`email`, `key1`, `expDate`) VALUES ('".$email."', '".$key."', '".$expDate."');");
                $output='<p>Dear user,</p>';
                $output.='<p>Please click on the following link to reset your password.</p>';
                $output.='<p>-------------------------------------------------------------</p>';
                $output.='<p><a href="localhost/dcms/reset-password.php?key='.$key.'&email='.$email.'&action=reset" target="_blank">localhost/dcms/reset-password.php?key='.$key.'&email='.$email.'&action=reset</a></p>'; 
                $output.='<p>-------------------------------------------------------------</p>';
                $output.='<p>Please be sure to copy the entire link into your browser. The link will expire after 1 day for security reason.</p>';
                $output.='<p>If you did not request this forgotten password email, no action 
                is needed, your password will not be reset. However, you may want to log into 
                your account and change your security password as someone may have guessed it.</p>';   
                $output.='<p>Thanks,</p>';
                $output.='<p>Dental King Team</p>';
                $body = $output; 
                $mail = new PHPMailer;
                $mail->IsSMTP();
                $mail->SMTPAuth = true;
                $mail->Host = "tls://smtp.gmail.com";
                $mail->Port = 587;
                $mail->Username = "username@email.com";
                $mail->Password = "password";
                $mail->setFrom('noreply@demo.com', 'Dental King');
                $mail->addAddress($email);
                $mail->isHTML(true); 
                $mail->Subject = "Password Reset Request";
                $mail->Body = $body;

                if(!$mail->send()) {
                    echo 'Message could not be sent. ';
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                    exit;
                }
                $allok = 1;
            }
        }
    }
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Password Reset Request</title>
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
    <h3>Password Reset</h3><br><br>
    <?php
    if(sizeof($msg)>0)
    {
        foreach($msg as $m)
        {
            echo "<h3 style='color:red; width:75%'>".$m."</h3><br>";
        }
    }
    if($allok)
    echo "<h3 style='color:blue; width:75%'>Check your email for the password reset link.</h3><br>";
    ?>
    <form action = '' method='post'>
    <table>
        <tr><th>Email ID</th><td><input type='email' name='email' placeholder='username@email.com' required></td></tr>
        </table>
        <br><input type='submit' name='forgot_pass' value='Request Password Reset' class='example_e' style='width:50%'></form>
    </div>
    </center>
</body>
</html>