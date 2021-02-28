<?php
//用戶UUID紀錄開始 
session_start();
$get_config_index = file_get_contents("json\config.json");
$data = json_decode($get_config_index, true);


//用戶
if(isset($_POST['enter'])){
    if($_POST['name'] != ""){
        $_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
    }
    else{
        echo '<span class="error">請輸入暱稱</span>';
    }
}
//
if(isset($_POST['enter'])){
    //簡易加入訊息
    $login_message = "<div class='msgln'><span class='left-info'>使用者 <b class='user-name-left'>". $_SESSION['name'] ."</b> 加入聊天SKR.</span><br></div>";
    file_put_contents("log.html", $login_message, FILE_APPEND | LOCK_EX);
}

if(isset($_GET['logout'])){    
     
    //簡易離開訊息
    $logout_message = "<div class='msgln'><span class='left-info'>使用者 <b class='user-name-come'>". $_SESSION['name'] ."</b> 離開了聊天QQ.</span><br></div>";
    file_put_contents("log.html", $logout_message, FILE_APPEND | LOCK_EX);
     //重定向使用者
    session_destroy();
    header("Location: index.php"); 
}

function loginForm(){
    echo '
     <head>
     <style>
       body {
        background-image: url("img/backgroud.jpg");
        background-repeat: no-repeat, repeat;
        background-color: rgb(255,255,255);
        background-position:left top;
      }
      </style>
      </head>
            <div id="backgroudlogin">
            <div id="loginform">
            <p>E4S Web Simple Chat v.0.5.1</v></v></v></p>
            <p>立即輸入暱稱 開始聊天吧</p>
            <form action="index.php" method="post">
              <label for="name">暱稱 &mdash;</label>
              <input type="text" name="name" id="name" />
              <input type="submit" name="enter" id="enter" value="開始" />
            </form>
            <a href="https://github.com/EarthlyEric/SIMPLE-CHAT"><img src="/img/Github/GitHub-Mark-32px.png"></a>
            <a href="scp-music-player.html"><img src="/img/SCP/SCPICON32X.png"></a>
            <embed src="audio\SCP-Breach.mp3" autostart="true" hidden="true" loop="1" volume="25" width="0" height="0" controls="volumelever"></embed>
            </div>
            </div>
            ';
} 

?>
 
<!DOCTYPE html>
<html lang="zh_tw">
    <head>
        <meta charset="utf-8" />
        <link rel="Shortcut Icon" type="image/x-icon" href="/icon/E4S_ICON.ico" />
        <title>E4S CHAT</title>
        <meta name="description" content="E4S CHAT" />
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>
    <?php
    if(!isset($_SESSION['name'])){
        loginForm();
    }
    else {
        
     //取得IP
     if(!empty($_SERVER['HTTP_CLIENT_IP'])){
     $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
    $ip = $_SERVER['REMOTE_ADDR'];
    }
    //IP更多資料
    $ip2 = $ip; 
    $s = file_get_contents('http://ip2c.org/'.$ip2);
    switch($s[0])
    {
    case '0':
    $ipshow = '不明錯誤';
    break;
    case '1':
    $reply = explode(';',$s);
    $ipshow = $reply[3];
    break;
    case '2':
    $ipshow = '無資料';
    break;
    }
    ?>  
        <div id="wrapper">
            <div id="menu">
                <p class="welcome">歡迎, <b><?php echo $_SESSION['name']; ?>進入聊天室<p class="IP">>>[IP=<?php echo $ip; ?>][國家:<?php echo $ipshow; ?>]<<</p></b></p>
                <p class="logout"><a id="exit" href="#">離開聊天</a></p>
            </div>
 
            <div id="chatbox">
            <?php
            if(file_exists("log.html") && filesize("log.html") > 0){
                $contents = file_get_contents("log.html");          
                echo $contents;
            }
            ?> 
            </div>
            <form name="message" action="post.php">
                <input name="usermsg" type="text" id="usermsg" />
                <input name="submitmsg" type="submit" id="submitmsg" value="傳送" />
            </form>
            </div>
        <script type="text/javascript" src="/js/jquery.min.js"></script>
        <script type="text/javascript" src="/js/post.js"></script>
    </body>
</html>
<?php
}
?>