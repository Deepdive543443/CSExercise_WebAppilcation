<?php
// Server and db connection
$mysql_host="krier.uscs.susx.ac.uk";
 
 $mysql_database="G6077_qf28";    // name of the database, it is empty for now
 $mysql_user="qf28";    // type your username
 $mysql_password="Mysql_491641";  //  type the password, it is Mysql_<Personcod> You will need to replace person code with number from your ID card.
 // 


// Create connection
// $conn = new mysqli($localhost,$rootUser, $rootPassword, $db);
$conn = new mysqli($mysql_host, $mysql_user,$mysql_password, $mysql_database) or die ("could not connect to the server");

// values come from user, through webform
 $username =$_POST['txtUsername'];
 $password = $_POST['txtPassword'];

//  $usernamehash = hash('sha256',  $username);
 $usernamehash = openssl_encrypt($username, "aes-256-cbc", "LoveJoy", 0, "1145141919810114");

 // Check connection
 if ($conn->connect_error)
 {
  die ("Connection failed" .$conn->connect_error);
  }

// query
$userQuery = "SELECT * FROM SystemUser";
$userResult = $conn->query($userQuery);

// flag variable
$userFound = 0;

$title = "";
$message = "";
$admin = 0;

  if ($userResult -> num_rows > 0)
  {
    while ($userRow = $userResult -> fetch_assoc())
    {
      if ($userRow['Username'] == $usernamehash)
      {
        $userFound = 1;
	  // if ($userRow['Password'] == $password)
    if(password_verify($password, $userRow['Password']))
	  {
	    $title .= "Hi " .$username . "!";
	    $message.= "Welcome to your gallery at G6077<br><a href='Gallery.html'><button class='submit_button' type='submit'>Jump</button></a>";
	  }
	  else
	  {
	    $title .= "Wrong Password or user";
        $message.= "Check your input";
	  }
    if ($userRow['Admin'] == '1'){
    $admin = 1;
    $title .= " the Administrators";
  }
      }
      

    }
  }

  if ($userFound == 0)
  {
    $title .= "Wrong Password or user";
    $message.= "This user was not found in our database";
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
                     <h1 class = "Register_title"><?php echo $title;?></h1>
                     <form class = "register_form" action="Gallery.php" method="post">
                         <h2><?php echo $message;?></h2> 
                         <input type="hidden" name="txtUsername" value=<?php echo $username?>>
                         <input type="hidden" name="Usernamehash" value=<?php echo $usernamehash?>>
                         <input type="hidden" name="admin" value=<?php echo $admin?>>                                        
                     </form>                  
                 </div>
             </div>   
        </main>
    </body>
</html>