<?php
//商品显示
error_reporting(E_ALL^E_NOTICE^E_WARNING); //屏蔽错误
include('./function/lib.php');


session_start();
$user = $_SESSION['user_id'];

//检查page参数
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
//把page与1对比，取最大值
$page = max($page,1);
//每页显示条数
$pageSize = 3;

//偏移量
$offset = ($page-1)*$pageSize;

$con = mysqlInt('localhost','root','','shop');

//查询总数
$sql = "SELECT COUNT(`id`) as total from `im_goods`";
$obj = mysqli_query($con,$sql);
$result = mysqli_fetch_assoc($obj);

$total = isset($result['total']) ? $result['total'] : 0;

unset($sql,$obj);

//id正序查询,浏览次数倒序查询
$sql = "SELECT `id`,`name`,`des`,`pic` FROM `im_goods` ORDER BY `id` asc , `view` desc LIMIT $offset,$pageSize";

$obj = mysqli_query($con,$sql);

$goods = array();
while($result = mysqli_fetch_assoc($obj))
{
    $goods[] = $result;

}

$pages =  pages($total,$page,$pageSize,5);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>M-GALLARY|首页</title>
    <link rel="stylesheet" type="text/css" href="./static/css/common.css"/>
    <link rel="stylesheet" type="text/css" href="./static/css/index.css"/>
</head>
<body>
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
    <div class="auth fr">
        <ul>
                <li><span>欢迎你：<?php echo $user['username'] ?></span></li>
                <a href="shopCart.php"><img src="./static/image/shopcart.png"></a>
                <li><a href="login.php">登录</a></li>
                <li><a href="register.php">注册</a></li>
                <li><a href="login_out.php">退出</a></li>

        </ul>
    </div>
</div>
<div class="content">
    <div class="banner">
        <img class="banner-img" src="./static/image/welcome.png" width="732px" height="372" alt="图片描述">
    </div>
    <div class="img-content">
        <ul>
            <?php foreach($goods as $v):?>
                <li>
                    <img class="img-li-fix" src="<?php echo $v['pic']?>" alt="<?php echo $v['name']?>">
                    <div class="info">
                        <a href="detail.php?id=<?php echo $v['id']?>"><h3 class="img_title"><?php echo $v['name']?></h3></a>
                        <p>
                            <?php echo $v['des']?>
                        </p>
                        <div class="btn">
                            <a href="detail.php?id=<?php echo $v['id']?>" class="edit">购买</a>
                            <a href="javascript:addCart(<?php echo $v['id']?>)" class="del">收藏</a>
                        </div>
                    </div>
                </li>
            <?php endforeach;?>

        </ul>
    </div>
    <?php echo $pages?>

</div>

<div class="footer">

    <p><span>M-GALLARY</span>©2017 POWERED BY IMOOC.INC</p>
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

