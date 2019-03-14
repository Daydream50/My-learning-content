<?php
header("Content-Type: text/html;charset=UTF-8");
include('./function/lib.php');
error_reporting(E_ALL^E_NOTICE^E_WARNING); //屏蔽错误

//对表单提交进行处理

if(!empty($_POST['username']))
{
    $username = test_input($_POST['username']);
    $password = test_input($_POST['password']);
    $repassword = test_input($_POST['repassword']);
    
    
    if(!$username)
    {
        showmsg(2,'账号不能为空');
    }
    if(!$password)
    {
        showmsg(2,'密码不能为空');
    }
    if(!$repassword)
    {
        showmsg(2,'确认密码不能为空');
    }
    if($password !== $repassword)
    {
        showmsg(2,'两次输入不一致');
    }
   
   	//数据库链接					
    $con = mysqlInt('localhost','root','','shop');
    $sql = "select count('id') as total from `im_admin` where username = '{$username}'";
    $obj = mysqli_query($con,$sql);
    $result = mysqli_fetch_assoc($obj);
    
    //验证用户名是否存在
    if(isset($result{'total'}) && $result{'total'} > 0)
    {
        showmsg(2,'用户名已经存在');
    }
    $password = createpassword($password);   //密码加密
    unset($obj,$result,$sql);    //释放变量
    //插入数据  
  
   
   $sql = "insert into im_admin (username,password,create_time) values
          ('{$username}','{$password}','{$_SERVER['REQUEST_TIME']}')";  //Unix时间戳 
    
    $obj = mysqli_query($con,$sql);
    
    if($obj)
    {
         showmsg(1,'注册成功','login.php');   //注册成功跳转登陆
    }
    else
    {
        showmsg(2,'注册失败');
    }
}
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>M-GALLARY|管理员注册</title>
    <link type="text/css" rel="stylesheet" href="./static/css/common.css">
    <link type="text/css" rel="stylesheet" href="./static/css/add.css">
    <link rel="stylesheet" type="text/css" href="./static/css/login.css">
</head>
<body>
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
    <div class="auth fr">
        <ul>
            <li><a href="login.php">登录</a></li>
            <li><a href="register.php">注册</a></li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="center">
        <div class="center-login">
            <div class="login-banner">
                <a href="#"><img src="./static/image/login_banner.png" alt=""></a>
            </div>
            <div class="user-login">
                <div class="user-box">
                    <div class="user-title">
                        <p>管理员用户注册</p>
                    </div>
                    <form class="login-table" name="register" id="register-form" action="register.php" method="post">
                        <div class="login-left">
                            <label class="username">用户名</label>
                            <input type="text" class="yhmiput" name="username" placeholder="Username" id="username">
                        </div>
                        <div class="login-right">
                            <label class="passwd">密码</label>
                            <input type="password" class="yhmiput" name="password" placeholder="Password" id="password">
                        </div>
                        <div class="login-right">
                            <label class="passwd">确认</label>
                            <input type="password" class="yhmiput" name="repassword" placeholder="Repassword"
                                   id="repassword">
                        </div>
                        <div class="login-btn">
                            <button type="submit">注册</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="footer">
    <p><span>M-GALLARY</span> ©2017 POWERED BY IMOOC.INC</p>
</div>
</body>
<script src="./static/js/jquery-1.10.2.min.js"></script>
<script src="./static/js/layer/layer.js"></script>

<script>

    $(function () {
        $('#register-form').submit(function () {
            var username = $('#username').val(),
                password = $('#password').val(),
                repassword = $('#repassword').val();
            if (username == '' || username.length <= 0) {
                layer.tips('用户名不能为空', '#username', {time: 2000, tips: 2});
                $('#username').focus();
                return false;
            }

            if (password == '' || password.length <= 0) {
                layer.tips('密码不能为空', '#password', {time: 2000, tips: 2});
                $('#password').focus();
                return false;
            }

            if (repassword == '' || repassword.length <= 0 || (password != repassword)) {
                layer.tips('两次密码输入不一致', '#repassword', {time: 2000, tips: 2});
                $('#repassword').focus();
                return false;
            }

            return true;
        })

    })
    
</script>
</html>


