<?php
//删除处理
header("Content-Type: text/html;charset=UTF-8");
//error_reporting(E_ALL^E_NOTICE^E_WARNING); //屏蔽错误
include('./function/lib.php');

if(!checkLogin())
{
    showmsg(2,'请登陆','login.php');
}

$goodsId = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : '';

if(!$goodsId)
{
    showmsg(2,'参数非法');
}

//ID查询
$con = mysqlInt('localhost','root','','shop');
$sql = "select * from im_goods where id= '{$goodsId}' LIMIT 1";
$obj = mysqli_query($con,$sql);
$result = mysqli_fetch_assoc($obj);

if(!$result)
{
    showmsg(2,'商品不存在','index.php');
}

//删除商品
unset($sql,$result);
$sql = "delete from `im_goods` where `id` = '{$goodsId}' LIMIT 1" ;
$result = mysqli_query($con,$sql);

if($result)
{
    echo "<script>alert('删除成功');history.go(-1);</script>";
}
else
{
    echo "<script>alert('删除失败');history.go(-1);</script>";
}
