<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

//SQL Connection
$mysql_host="krier.uscs.susx.ac.uk";
$mysql_database="G6077_qf28"; 
$mysql_user="qf28";
$mysql_password="Mysql_491641";

//User input from forgot.html
$Email = $_POST['Email'];

$Emailhash = hash('sha256', $Email);

$title = "";
$message = "";
$inputandbutton = "";
$code = "";


// // connect to the server
$connection = new mysqli($mysql_host, $mysql_user,$mysql_password, $mysql_database) or die ("could not connect to the server");

// Check if username already exists in the database. 
$userResult = $connection -> query("SELECT * FROM SystemUser");

$found = 0;
while ($userRow = mysqli_fetch_array($userResult)){
    // CHeck to see if the Email entered matches with any value in the database. 
    if ($userRow['Email'] == $Emailhash){
        $found = 1;
    }
}
if($found == 1){
        // add all of the contents of the variables to the SystemUser table
        $title .= "Confirm the security code";
        $code .= rand(1000,9999);

        $message .= "Code has been send to your Email address, please check out your mailbox to reset your password.";
        $inputandbutton .=  "<div class='regisform_block'>
                              <h2>Your security code</h2>
            <div class = 'input_withicon'>
         <img src = 'icons/passwordplus.png' class='inputbar_icon'>
            <input type ='text' name = 'EmailCode'>
         </div>
            <button class='submit_button' type='submit'>Confirm</button>
        </div>";
        
        //Send the Validation Code to user's mailbox
        $mail = new PHPMailer(true);
        
        try{
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->Host = 'smtp.qq.com';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
            $mail->Username = 'justinxane@qq.com';  
            $mail->Password = 'chuabbrazrwobfih';             
        
            $mail->setFrom('justinxane@qq.com', 'G6077 Lovejoy register request');  
            $mail->addAddress($Email, ' Register Information');
        
        
            $mail->isHTML(true);                           
            $mail->Subject = 'He Heh heh';
            $mail->Body    = '<html>' .
            ' <head></head>' .
            ' <body>' .
            '<h1>Reset your password</h1>'.
            ' <h2>Your PIN is</h2>' .
            '<h3>'.$code.'</h3>' .
            ' </body>' .
            '</html>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        
            if (!$mail->send()) {
                $message  .= 'Message could not be sent.';
                $message  .= 'Mailer Error: ' . $mail->ErrorInfo;
            }


        
        } catch (Exception $e) {
            $message  .= "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        
        
}else{
    $title .= "Error";
    $message  .= "We didn't find your Email address in database.";
}

?>


<html lang = "en">
    <meta charset="utf-8">
    <head>
        <title>Forgot your password</title>
        <link rel = "stylesheet" href="style.css">
    </head>
    <body>
        <header class = "Navigator">
            <h1 class = "logo">
               <img src="icons/icons8-key-128.png">
            </h1>
            <nav>
                <li><a href="index.html">HOME</a></li>
                <li><a href="register.html">Register</a></li>
                <li><a>ABOUT</a></li>
                <dl>Icon by icons8.com</dl>
            </nav>           
        </header>
        <main>
             <div class = "main_container">
                 <div class="regis_interface">
                     <h1 class = "Register_title"><?php echo $title;?></h1>
                     <form class = "register_form" action="reset.php" method="post">
                         <input type="hidden" name="Email" value=<?php echo $Email?>>
                         <input type="hidden" name="code" value=<?php echo $code?>>
                         <h2><?php echo $message;?></h2>
                         <?php echo $inputandbutton?>                                               
                     </form>                  
                 </div>
             </div>   
        </main>
    </body>
</html>