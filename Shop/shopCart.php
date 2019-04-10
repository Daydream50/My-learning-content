<?php
header("Content-Type: text/html;charset=UTF-8");
include('./function/lib.php');
//error_reporting(E_ALL^E_NOTICE^E_WARNING);

if(!checkLogin())
{
    showmsg(2,'请先登陆','login.php');
}
$user = $_SESSION['user_id'];

//查询购物车
$con = mysqlInt('localhost','root','','shop');
$sql = "select * from `shop_cart` where `user_id` = '{$user['id']}'";
$obj = mysqli_query($con,$sql);

$goods = array();

while($result = mysqli_fetch_assoc($obj))
{
    $goods[] = $result;
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>购物车</title>
    <link rel="stylesheet" type="text/css" href="./static/css/shopCart.css"/>


</head>
<body>

<div class="header">
<div class="logo"><img src="./static/image/logo.png"></div>
<div class="auth">
<ul>
    <li>欢迎你:<?php echo $user['username'] ?></li>
    <li><a href="index.php">首页</a></li>
    <li><a href="login.php">注册</a></li>
    <li><a href="login_out.php">退出</a></li>
</ul>
</div>
</div>


<div class="list">
    <div class="ul_list">
    <ul class="ul_one">
        <li style="padding:1% 0 0 1%;"><input input type="checkbox" name="chb[]" id="all" class="qx" onclick="quanxuan(this)"  style="zoom:130%;"  /><p>全选</p></li>
        <li style="  padding:1% 0 0 5% ">商品信息</li>
        <li style=" padding:1% 0 0 28%">单价</li>
        <li style=" padding:1% 0 0 10%;">数量</li>
        <li style="padding:1% 0 0 15%;">小计</li>
        <li style="padding:1.5% 0 0 10%;">操作</li>
    </ul>
    </div>
</div>
    <div class="show">
        <?php  foreach ($goods as $v):  ?>
        <ul class="show_ul">

            <li style="padding:1% 0 0 1%;"><input type="checkbox" class="qx"   style="zoom:135%;" /></li>
            <li  style="padding:1% 0 0 2%;" >
                <img src="<?php echo $v['pic']   ?>"  width="80px" height="80px"/>
                <p style="float: right; padding:15% 0 0 0"><?php echo $v['name']   ?></p>
            </li>
            <li style=" padding:5% 0 0 120px"><?php echo $v['price'] ?></li>
            <p style="display: block">
            <li style="padding: 4% 0 0 70px;">

                <ul class="btn-numbox">
                    <li>
                        <ul>
                            <li><input type="text" class="input-num" onblur="changeNum(<?php echo  $v['product_id'] ?>,this.value)"  value="<?php  echo $v['num'] ?>" /></li>
                        </ul>
                    </li>
                </ul>

            </li>
            <p/>

            <li  style="padding:5% 0 0 16%;"><?php echo $v['num'] * $v['price']?></li>
           <a href="javascript:delPro(<?php echo $v['product_id'] ?>);"> <li style="padding:5% 0 0 10%;" >删除</li></a>
        </ul>

        <?php  endforeach;  ?>
    </div>


<div class="foot_ul" >
<div class="foot" >

    <ul class="three" >
        <li style="margin: 2% 1% 0 1%"><input type="checkbox" onclick="quanxuan(this)"   />全选</li>
        <li style="margin: 2.4% 1% 0 65%; font-size:15px;">已选商品0件</li>
        <li style="margin: 2.4% 1% 0 2%; font-size: 18px;">￥0.00</li>
        <li class="ul_li" style="margin: 2% 0% 0% 1%;"><span class="nums">结算</span></li>
    </ul>

</div>
</div>
</div>
</body>
<script src="./static/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript">

    function quanxuan(a)
    {
        //找到下面所有的复选框
        var ck =document.getElementsByClassName("qx");

        //遍历所有复选框，设置选中状态。
        for(var i=0;i<ck.length;i++)
        {
            if(a.checked)//判断全选按钮的状态是不是选中的
            {
                ck[i].setAttribute("checked","checked");//如果是选中的，就让所有的状态为选中。
            }
            else
            {
                ck[i].removeAttribute("checked");//如果不是选中的，就移除所有的状态是checked的选项。
            }
        }
    }



    function delPro(productid) {
        var url = "deleteProduct.php";
        var data = {"productid":productid};
        var success = function (response) {
            if(response.errno == 0) {
                alert('删除成功');
            }else {
                alert('删除失败');
            }
        }
        $.get(url,data,success,"json");
    }
</script>

</html>