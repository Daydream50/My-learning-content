<?php
//跳转页面
header("Content-Type: text/html;charset=UTF-8");
//type等于1操作成功 2：操作失败. in_array判断数组是否有1或2         
$type = isset($_GET['type']) && in_array(intval($_GET['type']), array(1, 2))? intval($_GET['type']):2;

//标题
$title = $type ==1?'操作成功':'操作失败';

//判断是否正确
$msg = isset($_GET['msg'])?trim($_GET['msg']):'登陆成功';
$url = isset($_GET['url'])?trim($_GET['url']):'';     //如果存在，trim，url。否则为null

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo "$title"; ?></title>
    <link rel="stylesheet" type="text/css" href="./static/css/common.css"/>
    <link rel="stylesheet" type="text/css" href="./static/css/done.css"/>
</head>
<body>
<div class="header">
    <div class="logo f1">
        <img src="./static/image/logo.png">
    </div>
</div>
<div class="content">
    <div class="center">
        <div class="image_center">
                <?php if($type==1): ?>
                <span class="smile_face">:)</span> 
                <?php else:?>         
                <span class="smile_face">:(</span>
                <?php endif; ?> //结束 
        </div>
        <div class="code">
            <?php echo"$msg" ?>
        </div>
        <div class="jump">
            页面在 <strong id="time" style="color: #009f95">3</strong> 秒 后跳转
        </div>
    </div>

</div>
<div class="footer">
    <p><span>M-GALLARY</span>©2017 POWERED BY IMOOC.INC</p>
</div>
</body>
<script src="./static/js/jquery-1.10.2.min.js"></script>
<script>
    $(function () {
        var time = 3;
        var url ="<?php echo"$url"; ?>" || null;  //js对php进行操作
        setInterval(function () {
            if (time > 1) 
            {
                time--;
                console.log(time);
                $('#time').html(time);
            }
            else 
            {
                $('#time').html(0);
                //判断是否跳转
                if(url)
                {
                    location.href=url;
                }
                else
                {
                    history.go(-1);
                }
            }
                
          
        }, 1000);

    })
</script>
</html>
