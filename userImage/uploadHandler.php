<?php 
error_reporting(0);
$mysql_host="krier.uscs.susx.ac.uk";
$mysql_database="G6077_qf28"; 
$mysql_user="qf28";
$mysql_password="Mysql_491641";

$connection = new mysqli($mysql_host, $mysql_user,$mysql_password, $mysql_database) or die ("could not connect to the server");

$username = $_POST['txtUsername'];
$usernamehash = $_POST['Usernamehash'];
$Description = $_POST['Description'];
$Content = $_POST['Content'];
$admin = $_POST['admin'];


$key_query = "SELECT `key` FROM `userKey` WHERE  `Username`= ? ;";
$stmt = $connection -> prepare($key_query);
$stmt -> bind_param("s", $usernamehash);
$stmt-> bind_result($col1);
$stmt -> execute();
while($stmt ->fetch()){
    $privateKey = openssl_decrypt($col1, "aes-256-cbc", "LoveJoy", 0, "1145141919810114");
}
$stmt->close();

// echo $key_decrypt;

$usernamehash_private = openssl_encrypt($username, "aes-256-cbc", "LoveJoy", 0, $privateKey);
$Descriptionhash =openssl_encrypt($Description, "aes-256-cbc", "LoveJoy", 0, $privateKey);
$Contenthash =openssl_encrypt($Content, "aes-256-cbc", "LoveJoy", 0, $privateKey);

// var_dump($_FILES); // 区别于$_POST、$_GET  
$file = $_FILES["img"];
if ($file["error"] == 0){
    if($file["error"]==0){
        $typeArr = explode("/",$file["type"]);
        if($typeArr[0] == "image"){
            $imgType = array("png","jpg","jpeg","gif");
            if(in_array($typeArr[1],$imgType)){
                mkdir($username,0777);
                $imgname = $username."/".time().".".$typeArr[1]; 
                $imgnamehash = openssl_encrypt($imgname, "aes-256-cbc", "LoveJoy", 0, $privateKey);
                $sql = "INSERT INTO `userImage` (`Username`, `path`, `Description`, `Content`) VALUES (?, ?, ?, ?);";
                $stmt = $connection-> prepare($sql);
                $stmt->bind_param("ssss",$usernamehash_private,$imgnamehash, $Descriptionhash, $Contenthash);

                if(move_uploaded_file($file["tmp_name"], $imgname) && $stmt->execute()){
                    echo "uploaded";
                }else{
                    echo "Not uploaded";
                }
            }
        }
    }else{
       echo $file["error"]; 
    }

}else{
    echo "Not uploaded...";
}
?>

<form class="login_form" form action='../Gallery.php' method='POST'>
                        <input type ="hidden" name = "txtUsername" value = <?php echo $username?>>
                        <input type = "hidden" name = "Usernamehash" value = <?php echo $usernamehash?>>
                        <input type = "hidden" name = "admin" value = <?php echo $admin?>>
                        <button type="submit">Jump</button>
                    </form> 