<?php
 
session_start();

if(isset($_POST['enter'])){
    if($_POST['name'] != ""){
        $_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
    }
    else{
        echo '<span class="error">請輸入暱稱</span>';
    }
}

if(isset($_POST['enter'])){
    
    $login_message = "<div class='msgln'><span class='left-info'>使用者 <b class='user-name-left'>". $_SESSION['name'] ."</b> 加入聊天SKR.</span><br></div>";
    file_put_contents("log.html", $login_message, FILE_APPEND | LOCK_EX);
}

if(isset($_GET['logout'])){    
     
    //Simple exit message
    $logout_message = "<div class='msgln'><span class='left-info'>使用者 <b class='user-name-come'>". $_SESSION['name'] ."</b> 離開了聊天QQ.</span><br></div>";
    file_put_contents("log.html", $logout_message, FILE_APPEND | LOCK_EX);
     
    session_destroy();
    header("Location: index.php"); //Redirect the user
}
 
function loginForm(){
    echo
    '<div id="loginform">
    <p>E4S Web Simple Chat v.0.3</v></p>
    <p>立即輸入暱稱 開始聊天吧</p>
    <form action="index.php" method="post">
      <label for="name">暱稱 &mdash;</label>
      <input type="text" name="name" id="name" />
      <input type="submit" name="enter" id="enter" value="開始" />
    </form>
    <a href="https://github.com/EarthlyEric/SIMPLE-CHAT"><img src="GitHub-Mark-32px.png"></a>
  </div>';
}
 
?>
 
<!DOCTYPE html>
<html lang="zh_tw">
    <head>
        <meta charset="utf-8" />
        <link rel="Shortcut Icon" type="image/x-icon" href="E4S_ICON.ico" />
 
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
    ?>
        <div id="wrapper">
            <div id="menu">
                <p class="welcome">歡迎, <b><?php echo $_SESSION['name']; ?>進入聊天室</b></p>
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
 
            <form name="message" action="">
                <input name="usermsg" type="text" id="usermsg" />
                <input name="submitmsg" type="submit" id="submitmsg" value="傳送" />
            </form>
        </div>
        <script type="text/javascript" src="jquery.min.js"></script>
        <script type="text/javascript">
            // jQuery Document
            $(document).ready(function () {
                $("#submitmsg").click(function () {
                    var clientmsg = $("#usermsg").val();
                    $.post("post.php", { text: clientmsg });
                    $("#usermsg").val("");
                    return false;
                });
 
                function loadLog() {
                    var oldscrollHeight = $("#chatbox")[0].scrollHeight - 20; //Scroll height before the request
 
                    $.ajax({
                        url: "log.html",
                        cache: false,
                        success: function (html) {
                            $("#chatbox").html(html); //Insert chat log into the #chatbox div
 
                            //Auto-scroll           
                            var newscrollHeight = $("#chatbox")[0].scrollHeight - 20; //Scroll height after the request
                            if(newscrollHeight > oldscrollHeight){
                                $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
                            }   
                        }
                    });
                }
 
                setInterval (loadLog, 100);
 
                $("#exit").click(function () {
                    var exit = confirm("你確定登出?");
                    if (exit == true) {
                    window.location = "index.php?logout=true";
                    }
                });
            });
        </script>
    </body>
</html>
<?php
}
?>