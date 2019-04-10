<?php

error_reporting(E_ALL^E_NOTICE^E_WARNING); //屏蔽错误
header("Content-Type: text/html;charset=UTF-8");
include('./function/lib.php');

$goodsId = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : '';
session_start();
$user = $_SESSION['user_id'];
$id = $_SESSION['user'];

if(!$goodsId)
{
    showmsg(2,'参数非法','index.php');
}


//根据商品Id查询商品信息
$con = mysqlInt('localhost','root','','shop');
$sql = "select * from im_goods where id= '{$goodsId}' LIMIT 1";
$obj = mysqli_query($con,$sql);
$result = mysqli_fetch_assoc($obj);

unset($sql,$obj);

$sql = "select `id`,`username` from `im_admin` where `id` = '{$result['user_id']}'";
$obj = mysqli_query($con,$sql);
$admin = mysqli_fetch_assoc($obj);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>M-GALLARY|<?php echo $result['name'] ?></title>
    <link rel="stylesheet" type="text/css" href="./static/css/common.css" />
    <link rel="stylesheet" type="text/css" href="./static/css/detail.css" />
</head>
<body class="bgf8">
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
    <div class="auth fr">
        <ul>
            <?php if($user): ?>
            <li>欢迎你 : <?php echo $user['username'] ?></li>
            <?php endif; ?>
            <?php if($id): ?>
            <li>管理员 : <?php echo $id['username'] ?></li>
            <?php endif; ?>
            <li><a href="index.php">首页</a></li>
            <li><a href="login.php">登录</a></li>
            <li><a href="register.php">注册</a></li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="section" style="margin-top:20px;">
        <div class="width1200">
            <div class="fl"><img src="<?php echo $result['pic'] ?>" width="720px" height="432px"/></div>
            <div class="fl sec_intru_bg">
                <dl>
                    <dt><?php echo $result['name'] ?></dt>
                    <dd>
                        <p>发布人：<span><?php echo $admin['username'] ?></span></p>
                        <p>发布时间：<span><?php echo date('Y年m月d日',$result['create_time']) ?></span></p>
                        <p>修改时间：<span><?php echo date('Y年m月d日',$result['update_time']) ?></span></p>

                    </dd>
                </dl>
                <ul>
                    <li>售价：<br/><span class="price"><?php echo $result['price'] ?></span>元</li>
                    <li class="btn"><a href="javascript:;" class="btn btn-bg-red" style="margin-left:38px;">立即购买</a></li>
                    <li class="btn"><a href = "javascript:addCart(<?php echo $goodsId ?>)" class="btn btn-sm-white" style="margin-left:8px;">收藏</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="secion_words">
        <div class="width1200">
            <div class="secion_wordsCon">
                <?php echo $result['content'] ?>
        </div>
    </div>
</div>
<div class="footer">
    <p><span>M-GALLARY</span>©2017 POWERED BY IMOOC.INC</p>
</div>
</div>
</body>
<script src="./static/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript">
    function addCart(productid) {
        //ajax请求addCart.php完成数据的添加
        var url = "addCart.php";
        var data = {"productid":productid,"num":1};

        var success = function(response) {
            if(response.errno == 0){
                alert('添加成功');
            }
            else {
                alert('添加失败');
            }
        }
        $.post(url,data,success,'json');
    }
</script>
</html>

