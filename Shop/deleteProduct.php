<?php

session_start();
$user = $_SESSION['user_id'];
$userid = $user['id'];
$productid = intval($_GET['productid']);

//删除数据表
try{
    $pdo = new PDO("mysql:host=localhost;dbname=shop","root","");
    $pdo->query("set names utf8");
    $sql = "delete from shop_cart where product_id = ? and  user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($productid,$userid));
    $rows = $stmt->rowCount();
}catch (PDOException $e){
    echo $e->getMessage();
}

if($rows)
{
    $response = array(
        'errno' => 0,
        'errms' => 'yes',
        'data'  => true,
    );
}
else
{
    $response = array(
        'errno' => -1,
        'errms' => 'error',
        'data'  => false,
    );
}

echo json_encode($response);