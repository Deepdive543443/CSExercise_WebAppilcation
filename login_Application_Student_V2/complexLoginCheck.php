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

 echo "<table border='1'>";
  if ($userResult -> num_rows > 0)
  {
    while ($userRow = $userResult -> fetch_assoc())
    {
      if ($userRow['Username'] == $username)
      {
        $userFound = 1;
	  if ($userRow['Password'] == $password)
	  {
	    echo "Hi" .$username . "!";
	    echo "<br/> Welcome to our website";
	  }
	  else
	  {
	    echo "Wrong Password";
	  }
      }
    }
  }
  echo "</table>";

  if ($userFound == 0)
  {
   echo "This user was not found in our database";
  }

?>
