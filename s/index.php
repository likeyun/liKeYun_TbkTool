<style>
    #notfoundicon{
        width: 130px;
        height: 130px;
        margin:80px auto 20px;
    }
    #notfoundicon img{
        width: 130px;
        height: 130px;
    }
</style>
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0,viewport-fit=cover">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php

$key = substr(trim($_GET["id"]),2);

// 引入数据库配置
include '../Db_Connect.php';

// 连接数据库
$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
mysqli_query($conn, "SET NAMES UTF-8");

// 根据key去服务器数据库查询对应的优惠券中间页
$Get_zjyUrl_Sql = "SELECT * FROM tbk_zjy WHERE zjy_dwz like '%$key%'";
$result_zjyUrl_Sql = $conn->query($Get_zjyUrl_Sql);
if ($result_zjyUrl_Sql->num_rows > 0) {
    while($row_zjyUrl = $result_zjyUrl_Sql->fetch_assoc()) {
        
        $zjy_yuming = $row_zjyUrl['zjy_yuming'];
        $zjy_id = $row_zjyUrl['zjy_id'];
        
        // 拼接长链接
  		$longUrl_ = substr($zjy_yuming.dirname(dirname($_SERVER["REQUEST_URI"]))."/",-2);
  		if($longUrl_ == '//'){
          $longUrl = $zjy_yuming.dirname(dirname($_SERVER["REQUEST_URI"]))."index.php?zid=".$zjy_id;
        }else{
          $longUrl = $zjy_yuming.dirname(dirname($_SERVER["REQUEST_URI"]))."/"."index.php?zid=".$zjy_id;
        }
        // 跳转到长链接
        header('Location:'.$longUrl);
    }
}else{
    // 根据key去服务器数据库查询对应的活动中间页
    $Get_ActiveZjyUrl_Sql = "SELECT * FROM tbk_active_zjy WHERE active_dwz like '%$key%'";
    $result_ActiveZjyUrl_Sql = $conn->query($Get_ActiveZjyUrl_Sql);
    if ($result_ActiveZjyUrl_Sql->num_rows > 0) {
        while($row_ActiveZjyUrl = $result_ActiveZjyUrl_Sql->fetch_assoc()) {
            
            $ActiveZjy_yuming = $row_ActiveZjyUrl['active_yuming'];
            $ActiveZjy_id = $row_ActiveZjyUrl['active_id'];
            
            // 拼接长链接
      		$longUrl_ = substr($ActiveZjy_yuming.dirname(dirname($_SERVER["REQUEST_URI"]))."/",-2);
      		if($longUrl_ == '//'){
              $longUrl = $ActiveZjy_yuming.dirname(dirname($_SERVER["REQUEST_URI"]))."activezjy.php?activeid=".$ActiveZjy_id;
            }else{
              $longUrl = $ActiveZjy_yuming.dirname(dirname($_SERVER["REQUEST_URI"]))."/"."activezjy.php?activeid=".$ActiveZjy_id;
            }
            // 跳转到长链接
            header('Location:'.$longUrl);
        }
    }else{
        echo '<title>温馨提示</title>';
        echo '<div id="notfoundicon">
                <img src="../images/notfound.png" />
             </div>
             <p style="text-align:center;font-size:17px;color:#999;">该链接不存在或已被管理员删除</p>';
        exit;
    }
}

?>