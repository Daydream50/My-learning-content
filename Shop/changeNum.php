<?php

$productid  = intval($_POST['productid']);
$num = intval($_POST['num']);

session_start();
$user = $_SESSION['user_id'];
$userid = $user['id'];

try{
    $pdo = new PDO("mysql:host=localhost;dbname=shop","root","");
    $pdo->query("set names utf8");
    $sql = "update shop_cart set num = ? where user_id = ? and product_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($num,$userid,$productid));
    $rows = $stmt->rowCount();


}catch(PDOException $e){
    echo  $e->getMessage();
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


