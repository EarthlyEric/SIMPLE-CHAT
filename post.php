<?php
session_start();
date_default_timezone_set('Asia/Taipei');
if(isset($_SESSION['name'])){
    $text = $_POST['text'];
     
    $text_message = "<div class='msgln'><span class='chat-time'>".date("g:i A")."</span> <b class='user-name'>".$_SESSION['name']."</b> ".stripslashes(htmlspecialchars($text)).$ipshow."<br></div>";
    file_put_contents("log.html", $text_message, FILE_APPEND | LOCK_EX);
}

?>