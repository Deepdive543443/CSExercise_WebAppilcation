<?php
$mysql_host="krier.uscs.susx.ac.uk";
$mysql_database="G6077_qf28"; 
$mysql_user="qf28";
$mysql_password="Mysql_491641";

$connection = new mysqli($mysql_host, $mysql_user,$mysql_password, $mysql_database) or die ("could not connect to the server");

$username =$_POST['txtUsername'];
$usernamehash =$_POST['Usernamehash'];
$admin = $_POST['admin'];
$gallery_block = "";
$a = array();

if($admin == 1){
    $key_query = "SELECT * FROM `userKey`";
    $stmt = $connection -> prepare($key_query);
    $stmt-> bind_result($col1,$col2);
    $stmt -> execute();
    while($stmt -> fetch()){
        $user = openssl_decrypt($col1, "aes-256-cbc", "LoveJoy", 0, "1145141919810114");
        $key = openssl_decrypt($col2, "aes-256-cbc", "LoveJoy", 0, "1145141919810114");
        $usernamehash_private = openssl_encrypt($user, "aes-256-cbc", "LoveJoy", 0, $key);
        $a[$usernamehash_private] = $key;
    }

    foreach ($a as $user => $privateKey){
        $block_query = "SELECT `path`,`Description`,`Content` FROM `userImage` WHERE  `Username`= ?;";
    $stmt = $connection -> prepare($block_query);
    $stmt -> bind_param("s", $user);
    $stmt-> bind_result($col1,$col2,$col3);
    $stmt -> execute();
    while($stmt ->fetch()){
        $gallery_block .= "<div class='gallery_block'>
        <h2>".openssl_decrypt($col2, "aes-256-cbc", "LoveJoy", 0, $privateKey)."</h2>
        <img src = 'userImage/".openssl_decrypt($col1, "aes-256-cbc", "LoveJoy", 0, $privateKey)."'>
        <div class='gallery_block_module'>
        <div class = 'content_display'>
        <p class = 'warp'>".openssl_decrypt($col3, "aes-256-cbc", "LoveJoy", 0, $privateKey)."</p>
        </div>
        </div>
        <div class='gallery_block_module'>
        <h3>".openssl_decrypt($user, "aes-256-cbc", "LoveJoy", 0, $privateKey)."</h3>
        </div>
        </div>";
}

    }
}else{
    //Obtain private key
    $key_query = "SELECT `key` FROM `userKey` WHERE  `Username`= ? ;";
    $stmt = $connection -> prepare($key_query);
    $stmt -> bind_param("s", $usernamehash);
    $stmt-> bind_result($col1);
    $stmt -> execute();
    while($stmt ->fetch()){
        $privateKey = openssl_decrypt($col1, "aes-256-cbc", "LoveJoy", 0, "1145141919810114");
    }
    // echo $privateKey;
    $usernamehash_private = openssl_encrypt($username, "aes-256-cbc", "LoveJoy", 0, $privateKey);

    //Obtain path, description and content
    $block_query = "SELECT `path`,`Description`,`Content` FROM `userImage` WHERE  `Username`= ?;";
    $stmt = $connection -> prepare($block_query);
    $stmt -> bind_param("s", $usernamehash_private);
    $stmt-> bind_result($col1,$col2,$col3);
    $stmt -> execute();
    while($stmt ->fetch()){
        $gallery_block .= "<div class='gallery_block'>
        <h2>".openssl_decrypt($col2, "aes-256-cbc", "LoveJoy", 0, $privateKey)."</h2>
        <img src = 'userImage/".openssl_decrypt($col1, "aes-256-cbc", "LoveJoy", 0, $privateKey)."'>
        <div class='gallery_block_module'>
        <div class = 'content_display'>
        <p class = 'warp'>".openssl_decrypt($col3, "aes-256-cbc", "LoveJoy", 0, $privateKey)."</p>
        </div>
        </div>
        <div class='gallery_block_module'>
        </div>
        </div>";
}
}
?>

<html lang = "en">
    <meta charset="utf-8">
    <head>
        <title>Gallery</title>
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
            <div class="gallery_container">
            <div class="gallery_block">
            <h2>Example Title</h2>
               <img src = "pandawithcat.jpg">
                <div class="gallery_block_module">
                       <h2>Example Content</h2>
                     <div class = "content_display">
                         <p class = "warp">wefwe wef ewef wef wefwaowfos odfksdfowemf fsdkmcf dfs dfdsfwr o ko dfstsch asic biausbci asscasocmaopscm poasmcp oa smcp oamspc omaps comapo scmpa osmcpa omsco pasmcpoam s cpmpa socm opas cmopa smcop amop csmp oasm cop asmc</p>
                     </div>
                </div>
                <div class="gallery_block_module">
                </div>
            </div>
            <?php echo $gallery_block;?>
            <div class="gallery_block">
                <form action="userImage/uploadHandler.php" method="post" enctype="multipart/form-data">
                <div class="gallery_block_module">
                <h2>Upload Img:</h2>
                    <input type="hidden" name="txtUsername" value=<?php echo $username?>>
                    <input type="hidden" name="Usernamehash" value=<?php echo $usernamehash?>>
                    <input type="hidden" name="admin" value=<?php echo $admin?>>
                    <input type="file" name="img"/>
                </div>
                    
                <div class="gallery_block_module">
                       <h2>Description</h2>
                     <div class = "decription">
                     <textarea typetype ="text" name = "Description"></textarea>
                     </div>
                </div>

                <div class="gallery_block_module">
                       <h2>Content</h2>
                     <div class = "content">
                     <textarea typetype ="text" name = "Content"></textarea>
                     </div>
                </div>
                <div class="gallery_block_module">
                    <button class="submit_button" type="submit">Upload</button>
                </div>
                </form>
                </div> 
                
                <div class="gallery_block">
            <h2>Delete all photo</h2>
               <form action = "userImage/delete.php" method="post">
                    <input type="hidden" name="txtUsername" value=<?php echo $username?>>
                    <input type="hidden" name="Usernamehash" value=<?php echo $usernamehash?>>
                    <input type="hidden" name="admin" value=<?php echo $admin?>>
                    <div class="gallery_block_module">
                    <button class="submit_button" type="submit">Delete</button>
                    </div>
               </form>
            </div>
            </div>
        </main>
    </body>
</html>


