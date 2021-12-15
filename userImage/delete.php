<?php
$mysql_host="krier.uscs.susx.ac.uk";
$mysql_database="G6077_qf28"; 
$mysql_user="qf28";
$mysql_password="Mysql_491641";

$connection = new mysqli($mysql_host, $mysql_user,$mysql_password, $mysql_database) or die ("could not connect to the server");

$username =$_POST['txtUsername'];
$usernamehash =$_POST['Usernamehash'];
$admin = $_POST['admin'];

function deldir($dir) {
	$dh = opendir($dir);
	while ($file = readdir($dh)) {
		if($file != "." && $file!="..") {
		$fullpath = $dir."/".$file;
		if(!is_dir($fullpath)) {
			unlink($fullpath);
		} else {
			deldir($fullpath);
		}
		}
	}
	closedir($dh);
    if(rmdir($dir)) {
		return true;
	} else {
		return false;
	}
	
}



$key_query = "SELECT `key` FROM `userKey` WHERE  `Username`= ? ;";
$stmt = $connection -> prepare($key_query);
$stmt -> bind_param("s", $usernamehash);
$stmt-> bind_result($col1);
$stmt -> execute();
while($stmt ->fetch()){
    $privateKey = openssl_decrypt($col1, "aes-256-cbc", "LoveJoy", 0, "1145141919810114");
}


$usernamehash_private = openssl_encrypt($username, "aes-256-cbc", "LoveJoy", 0, $privateKey);

//Delete folder refer to user
// DELETE FROM `userKey` WHERE  `Username`='sacas';
// deldir($username);
$sql = "DELETE FROM `userImage` WHERE  `Username`= ? ";
$stmt = $connection-> prepare($sql);
$stmt->bind_param("s",$usernamehash_private);
if($stmt ->execute() && deldir($username)){
    echo "Delete all gallery successfully";
}else{
    echo "Error";
}
?>

<form class="login_form" form action='../Gallery.php' method='POST'>
                        <input type ="hidden" name = "txtUsername" value = <?php echo $username?>>
                        <input type = "hidden" name = "Usernamehash" value = <?php echo $usernamehash?>>
						<input type="hidden" name="admin" value=<?php echo $admin?>>
                        <button type="submit">Jump</button>
                    </form> 