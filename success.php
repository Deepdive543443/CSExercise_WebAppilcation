<?php
//SQL Connection
$mysql_host="krier.uscs.susx.ac.uk";
$mysql_database="G6077_qf28"; 
$mysql_user="qf28";
$mysql_password="Mysql_491641";

// // connect to the server
$connection = new mysqli($mysql_host, $mysql_user,$mysql_password, $mysql_database) or die ("could not connect to the server");

$forename = $_POST['txtForename'];
$surname = $_POST['txtSurname'];
$username = $_POST['txtUsername'];
$email1 = $_POST['txtEmail1'];
$email2 = $_POST['txtEmail2'];
$password1 = $_POST['txtPassword1'];
$password2 = $_POST['txtPassword2'];
$code = $_POST['code'];
$emailCode = $_POST['emailCode'];
$key = rand(1000000000000000,9999999999999999);


//hashing
// $usernamehash = hash('sha256', $username);

$usernamehash = openssl_encrypt($username, "aes-256-cbc", "LoveJoy", 0, "1145141919810114");

$password1hash = password_hash($password1, PASSWORD_DEFAULT);
$forenamehash = hash('sha256', $forename);
$surnamehash = hash('sha256', $surname);
$email1hash = hash('sha256', $email1);
// $keyhash = hash('sha256', $key);
$keyhash = openssl_encrypt($key, "aes-256-cbc", "LoveJoy", 0, "1145141919810114");

//Message
$title = "";
$message = "";

if($code == $emailCode){
    $sql = "INSERT INTO SystemUser (Username, Password, Forename, Surname, Email, Admin) VALUES (?, ?, ?, ?, ?, '0')";
    $stmt = $connection -> prepare($sql);
    $stmt -> bind_param("sssss",$usernamehash, $password1hash, $forenamehash, $surnamehash, $email1hash);
    if($stmt -> execute()){
        $sql2 = "INSERT INTO `userKey` (`Username`, `key`) VALUES (?, ?)";
        $stmt = $connection -> prepare($sql2);
        $stmt -> bind_param("ss",$usernamehash, $keyhash);
        if($stmt -> execute()){
            $title .= "Registration successful!";
            $message .= "Hello " .$forename ." ".$surname ."<br/>";
            $message .= "You have become a part of the Lovejoy at G6077!";
        }else{
            $title .= "Connection failed";
        $message .= "Failed to update your detail to Database. Check out the connection.";
        }       
    }else{
        $title .= "Connection failed";
        $message .= "Failed to update your detail to Database. Check out the connection.";
    }

}else{
    $title .= "Error";
    $message .= "Captcha mismatch"; 
}
?>

<html lang = "en">
    <meta charset="utf-8">
    <head>
        <title>Login</title>
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
                    <h1 class = "Register_title"><?php echo $title?></h1>
                    <h3><?php echo $message?></h3>
                 </div>
             </div>   
        </main>
    </body>
</html>