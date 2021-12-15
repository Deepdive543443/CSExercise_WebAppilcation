<?php
//SQL Connection
$mysql_host="krier.uscs.susx.ac.uk";
$mysql_database="G6077_qf28"; 
$mysql_user="qf28";
$mysql_password="Mysql_491641";


$Email = $_POST['Email'];
$code = $_POST['code'];
$EmailCode = $_POST['EmailCode'];

//connect to the server
$connection = new mysqli($mysql_host, $mysql_user,$mysql_password, $mysql_database) or die ("could not connect to the server");

$title = "";
$message = "";
$inputandbutton = "";

if ($code == $EmailCode){
    $title .= "Reset your password";
    $message .= "Enter your new Password";
    $inputandbutton .= "<div class='regisform_block'>
    <h2>New password</h2>
     <div class = 'input_withicon'>
  <img src = 'icons/password.png' class='inputbar_icon'>
     <input type ='password' name = 'newPassword'>
  </div>
     <button class='submit_button' type='submit'>Send</button>
 </div>";
}else{
    $title .= "Error";
    $message .= "The code you entered does not match.";
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
                     <form class = "register_form" action="resetSuccess.php" method="post">
                         <h2><?php echo $message;?></h2>
                         <input type="hidden" name="Email" value=<?php echo $Email?>>
                         <?php echo $inputandbutton;?>                                               
                     </form>                  
                 </div>
             </div>   
        </main>
    </body>
</html>