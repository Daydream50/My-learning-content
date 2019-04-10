<?php


//数据库链接
function mysqlInt ($host,$username,$pd,$dbname)
{
    $con = mysqli_connect($host,$username,$pd) or die('数据库链接失败');
    mysqli_select_db($con,$dbname) or die('选择数据库失败');
    mysqli_set_charset($con,"UTF8");
    
    return $con;
}

//密码加密

function createpassword ($password)
{
    if(!$password)
    {
        return fales;
    }
    return md5(md5($password).'xjy');   //加盐
}
//url传参

function showmsg ($type,$msg=null,$url=null)
{
    $toUrl = "Location:msg.php?type={$type}";
    //当msg为空时不写入
    $toUrl.=$msg?"&msg={$msg}":'';
    //当url为空时不写入
    $toUrl.=$url?"&url={$url}":'';
    
    header($toUrl);
    exit;
}



function checkLogin()
{
    session_start() ;  //开启会话

    if(!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])  )//检测是否已经登陆
    {
       return false;
    }

    return true;

}

//页面显示
//$total总条数,$currentPage,当前页面的页数，$pageSize每页显示的条数,$show分页显示几个按钮
function pages ($total, $currentPage, $pageSize, $show=6)
{
    $pageStr = '';
    //仅当总数大于每页显示条数，才进行分页

    if($total>$pageSize)
    {
        //总页数
        $totalPage = ceil($total/$pageSize); //向上取整数

        //如果当前页大于总页数，就取总页数。否则取当前页
        $currentPage = $currentPage>$totalPage ? $totalPage : $currentPage;

        //分页起始页
        $from = max(1,($currentPage - intval($show/2)));

        //分页结束页
        $to = $from+$show-1;

        $pageStr .= '<div class="page-nav">';
        $pageStr .= '<ul>';

        //仅当当前页，大于1才出现首页和上一页
        if($currentPage>1)
        {
            $pageStr .="<li><a href='".pageUrl(1)."'>首页</a></li>";
            $pageStr .="<li><a href='".pageUrl($currentPage-1)."'>上一页</a></li>";
        }


        //当结束页大于总页
        if($to>$totalPage)
        {
            $to = $totalPage;
            $from = max(1,$to-$show+1);
        }

        if($from > 1)
        {
            $pageStr .='<li>...</li>';
        }

        //分页显示
        for($i = $from; $i<= $to; $i++)
        {
            if($i != $currentPage)     //$i不等于当前页
            {
                $pageStr .="<li><a href=' " .pageUrl($i)." '>{$i}</a></li>";
            }
            else
            {
                $pageStr .= "<li><span class='curr-page'>{$i}</span></li>";
            }
        }

        if($to < $totalPage)
        {
            $pageStr .= '<li>...</li>';
        }

        //仅当当前页小于总页
        if($currentPage<$totalPage)
        {
            $pageStr .="<li><a href='".pageUrl($currentPage+1)."'>下一页</a></li>";
            $pageStr .="<li><a href='".pageUrl($totalPage )."'>尾页</a></li>";
        }

        $pageStr .= '</ul>';
        $pageStr .= '</div>';

    }
    return $pageStr;
}

//获取当前url
function getUrl()
{
    $url .='';
    //判断端口是否是443
    $url .=$_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://';
    //获取当前用户主机名
    $url .=$_SERVER['HTTP_HOST'];
    //域名
    $url .=$_SERVER['REQUEST_URI'];

    return $url;

}
//生成url
function pageUrl($page, $url = '')
{
    $url = empty($url) ? getUrl() : $url;
    //查询是否存在 '?'
    $pos = strpos($url,'?');

    if($pos === false)
    {
        $url .='?page='.$page;
    }

    //如果存在page，要拿到page后面的其他url参数
    else
    {
        $querString = substr($url,$pos+1);      // +1去掉 '?'
       //把$querString转为数组
        parse_str($querString,$queryArry);

        if(isset($queryArry['page']))
        {
            unset($queryArry['page']);
        }
        //删除page，赋上重新提交的值
        $queryArry['page'] = $page;

        //将数组重新拼接成url字符串
        $queryStr = http_build_query($queryArry);

        $url = substr($url,0,$pos). '?' .$queryStr;


    }

  return $url;
}

?>