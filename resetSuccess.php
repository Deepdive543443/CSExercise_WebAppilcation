<?php
//SQL Connection
$mysql_host="krier.uscs.susx.ac.uk";
$mysql_database="G6077_qf28"; 
$mysql_user="qf28";
$mysql_password="Mysql_491641";

// $dsn="Mysql:host=$mysql_host;dbname=$mysql_database";

// // connect to the server
$connection = new mysqli($mysql_host, $mysql_user,$mysql_password, $mysql_database) or die ("could not connect to the server");

$Email = $_POST['Email'];
$newPassword = $_POST['newPassword'];

$Emailhash = hash('sha256', $Email);
$newPasswordhash = password_hash($newPassword, PASSWORD_DEFAULT);

$title = "";
$message = "";

$sql = "UPDATE SystemUser SET Password = ? WHERE Email = ?";
$stmt = $connection-> prepare($sql);
$stmt->bind_param("ss",$newPasswordhash,$Emailhash);
if($stmt->execute()){
    $title .= "Success";
    $message .= "Your password has been replaced successfully";
}else{
    $title .= "Error";
    $message .= "update fail"; 
}
?>

<html lang = "en">
    <meta charset="utf-8">
    <head>
        <title>reset</title>
        <link rel = "stylesheet" href="style.css">
    </head>
    <body>
        <header class = "Navigator">
            <h1 class = "logo">
               <img src="icons/icons8-key-128.png">
            </h1>
            <nav>
                <li><a href="index.html">HOME</a></li>
                <li><a href="register.html">Success</a></li>
                <li><a>ABOUT</a></li>
                <dl>Icon by icons8.com</dl>
            </nav>           
        </header>
        <main>
        <div class = "main_container">
                 <div class="regis_interface">
                     <h1 class = "Register_title"><?php echo $title;?></h1>
                     <form class = "register_form" action="resetSuccess.php" method="post">
                         <h2><?php echo $message;?></h2>                                         
                     </form>                  
                 </div>
             </div> 
        </main>
    </body>
</html>