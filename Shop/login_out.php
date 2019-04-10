<?php
header("Content-Type: text/html;charset=UTF-8");
include('./function/lib.php');
//清除session
session_start();
unset($_SESSION['user_id']);
showmsg(1,'退出成功','login.php');