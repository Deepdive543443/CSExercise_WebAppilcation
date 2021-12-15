<?php
// Dependencies
//PHPMailer
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

// // connect to the server
$connection = new mysqli($mysql_host, $mysql_user,$mysql_password, $mysql_database) or die ("could not connect to the server");

//Obtain register form from webpage
$forename = $_POST['txtForename'];
$surname = $_POST['txtSurname'];
$username = $_POST['txtUsername'];
$email1 = $_POST['txtEmail1'];
$email2 = $_POST['txtEmail2'];
$password1 = $_POST['txtPassword1'];
$password2 = $_POST['txtPassword2'];

$errorOccurred = 0;

$usernamehash = hash('sha256', $username);
$email1hash = hash('sha256', $email1);

$title = "";
$returnMessage = "";
$inputandbutton = "";
$code = "";

 // Make sure that all text boxes were not blank.
if ($forename == ""){
    // echo "Forname was blank !<br/>";
    $returnMessage .= "Forname was blank<br>";
    $errorOccurred = 1;
}

if ($surname == ""){
    $returnMessage .= "Surname was blank <br/>";
    $errorOccurred = 1;
}

if ($username==""){
    $returnMessage .= "username was blank !<br/>";
    $errorOccurred = 1;
}

if ($email1=="" OR $email2==""){
    $returnMessage .= "Email not provided <br/>";
    $errorOccurred = 1;
}

if ($password1=="" OR $password2=""){
    $returnMessage .= "Password empty, check it. <br/>";
    $errorOccurred = 1;
}

 // Check if username already exists in the database. 
$userResult = $connection -> query("SELECT * FROM SystemUser");
 
 //Loop through from the first to the last record
while ($userRow = mysqli_fetch_array($userResult)){
    // CHeck to see if the curren user' username matchs the one from the user
    if ($userRow['Username'] == $usernamehash){
        $returnMessage .= "The username has already been used ! <br/>";
        $errorOccurred = 1;
	}
}

// Check to see if the email address is registered.
$userResult = $connection -> query("SELECT * FROM SystemUser");

// Loop from the first to the last record
while ($userRow = mysqli_fetch_array($userResult)){
    // CHeck to see if the Email entered matches with any value in the database. 
    if ($userRow['Email'] == $email1hash){
        $returnMessage .= "This email address has already been used. <br/>";
        $errorOccurred = 1;
    }
}

// Check to make sure that email address contain @
if (strpos ($email1, "@") == false OR strpos($email2,"@") == false){
    $returnMessage .= "The second email address are not valid <br/>";
}

// Check to make sure that emails match
if($email1 != $email2){
    $returnMessage .= "Emails do not match <br/>";
}


if ($errorOccurred == 0){
// add all of the contents of the variables to the SystemUser table
$title .= "Confirm the register code";
$code .= rand(1000,9999);
$returnMessage .= "PIN has been send to your Email address, please check out your mailbox to complete the registration.";
$inputandbutton .=  "<div class='regisform_block'>
                        <h2>PIN</h2>
                         <div class = 'input_withicon';>
                          <img src = 'icons/passwordplus.png' class='inputbar_icon'>
                          <input type ='text' name = 'emailCode'>
                         </div>
                        <button class='submit_button' type='submit'>Register</button>
                    </div>";

//Send the PIN to user's mailbox
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
    $mail->addAddress($email1, ' Register Information');


    $mail->isHTML(true);                                  
    $mail->Subject = 'Your registration at G6077 Love & Joy';
    $mail->Body    = '<html>' .
    ' <head></head>' .
    ' <body>' .
    '<h1>Your registration at G6077 Love & Joy</h1>'.
    ' <h2>Your PIN is</h2>' .
    '<h3>'.$code.'</h3>' .
    ' </body>' .
    '</html>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if (!$mail->send()) {
        $returnMessage .= 'Message could not be sent.';
        $returnMessage .= 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        $returnMessage .= 'Register Message has been sent<br/>';
    }

} catch (Exception $e) {
    $returnMessage .= "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

}else{
    $title .= "Error";
}
?>

<html lang = "en">
    <meta charset="utf-8">
    <head>
        <title>Register</title>
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
                    <form class = "register_form" action="success.php" method="post">
                        <h2><?php echo $returnMessage;?></h2>
                        <input type="hidden" name="txtForename" value=<?php echo $forename?>>
                        <input type="hidden" name="txtSurname" value=<?php echo $surname?>>
                        <input type="hidden" name="txtUsername" value=<?php echo $username?>>
                        <input type="hidden" name="txtEmail1" value=<?php echo $email1?>>
                        <input type="hidden" name="txtEmail2" value=<?php echo $email2?>>
                        <input type="hidden" name="txtPassword1" value=<?php echo $password1?>>
                        <input type="hidden" name="txtPassword2" value=<?php echo $password2?>>
                        <input type="hidden" name="code" value=<?php echo $code?>>
                        <?php echo $inputandbutton;?>
                    </form>                  
                 </div>
             </div>   
        </main>
    </body>
</html>

