<?php

error_reporting(E_ALL^E_NOTICE^E_WARNING);
//接受传递过来的post参数
//准备要添加的购物车数据
//完成购物车数据的添加操作
//返回最终添加的结果

$product_id = intval($_POST['productid']);
$num = intval($_POST['num']);

session_start();
$user = $_SESSION['user_id'];
$now = $_SERVER['REQUEST_TIME'];



try{
    //商品查询
    $pdo = new PDO("mysql:host=localhost;dbname=shop","root","");
    $pdo->query("set names utf8");
    $sql = "select `id`,`name`,`price`,`pic`,`user_id` from im_goods where id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($product_id));
    $data = $stmt->fetch(PDO::FETCH_ASSOC);   //关联数组形式


    unset($sql,$stmt);
    $user_id =  $user['id'];  //用户id
    $product_id = $data['id'];             //商品id
    $name = $data['name'];         //商品名称
    $price = $data['price'];        //价格
    $pic = $data['pic'];            //图片
    $now = $_SERVER['REQUEST_TIME'];        //时间

    //查询商品是否在购物车当中
    $sql = "select * from `shop_cart` where  `product_id` = ?  and `user_id` = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($product_id,$user_id));
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if($data)
    {   //查询商品是否存在
        $sql = "update `shop_cart` set num = num+? where  `user_id` = ?  and  `product_id` = ?";
        $params = array($num,$user_id,$product_id);
    }
    else
    {
        //商品添加到购物车
        $sql = "insert into shop_cart(`user_id`,`product_id`,`name`,`price`,`pic`,`now`,`num`) values (?,?,?,?,?,?,?)";
        $params = array($user_id,$product_id ,$name,$price,$pic,$now,$num);
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->rowCount();

    if($rows)
    {
        $response = array(
            'errno' => 0,
            'errms' => '添加成功',
            'data'  => true,
        );
    }
    else
    {
        $response = array(
            'errno' => -1,
            'errms' => '添加失败',
            'data'  => false,
        );
    }
echo json_encode($response);

}catch (PDOException $e){
    echo $e->getMessage();
}
