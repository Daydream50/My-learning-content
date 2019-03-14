<?php
//编辑商品
header("Content-Type: text/html;charset=UTF-8");
error_reporting(E_ALL^E_NOTICE^E_WARNING); //屏蔽错误
include('./function/lib.php');
if(!checkLogin())
{
    showmsg(2,'请登录','login.php');
}

//检测是否已经登陆
if(!isset($_SESSION['user']) || empty($_SESSION['user']))
{
   showmsg(2,'请登录','login.php');
}

if(!empty($_POST['name']))
{
    //判断参数
    if(!$goodsId = intval($_POST['id']))
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

    $name = test_input($_POST['name']);            //画品名称
    $price = test_input($_POST['price']);           //价值
    $des =  test_input($_POST['des']);               //画品简介
    $content = test_input($_POST['content']);         //画品详情
    $now = $_SERVER['REQUEST_TIME'];                 //更新时间


    $nameLength = mb_strlen($name,'UTF-8');
    if($nameLength <=0 || $nameLength >30)
    {
      showmsg(2,'画品名称长度应该在1-30字符之内');  
    }
    
    if($price<=0 || $price>999999999)
    {
        showmsg(2,'价值应该在0~999999999之内');
    }
    
    $desLength = mb_strlen($des,'utf-8');
    if($desLength <= 0 || $desLength > 100)
    {
        showmsg(2,'商品简介应该在1~100字符之内');
    }
    
    if(empty($content))
    {
        showmsg(2,'画品详情不能为空');    
    }

    //更新数组
    $update = array(
        'name' => $name,
        'price' => $price,
        'des'  => $des,
        'content' => $content,
        'update_time' => $now
    );

    //仅当用户选择上传图片，才进行图片上传处理
    if($_FILES['file']['size'] > 0)
    {
        $pic = imgUpload($_FILES['file']);
        $update['pic'] = $pic;
    }

    //只更新被修改的文件
    foreach ($update as $k=> $v)
    {
        if($result[$k] == $v)
        {
            unset($update[$k]);
        }
    }

    if(empty($update))
    {
        echo "<script>alert('更新成功');history.go(-1);</script>";
    }

    //更新sql处理
    $updateSql = '';
    foreach($update as $k => $v)
    {
        $updateSql.= " `{$k}` = '{$v}' ,";
    }
    $updateSql = rtrim($updateSql,',');  //去除结尾最后一个逗号

    unset($sql,$obj,$result);

            //update `im_goods` set `name` = '{$name}',`price` = '{$price}'.... where `id` = $goodsId
    $sql = "update `im_goods` set $updateSql where  `id` = $goodsId";

    //当更新成功
    if($result = mysqli_query($con,$sql))
    {
        echo "<script>alert('更新成功');history.go(-1);</script>";
    }
    else
    {
        echo "<script>alert('更新失败');history.go(-1);</script>";
    }
}
else
{
    showmsg(2,'路由非法','index.php');
}

?>