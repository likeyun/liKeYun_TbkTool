<!DOCTYPE html>
<html>
<head>
  <title>淘宝客工具箱 - 系统设置</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://cdn.staticfile.org/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdn.staticfile.org/popper.js/1.15.0/umd/popper.min.js"></script>
  <script src="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <link href="https://cdn.bootcdn.net/ajax/libs/open-iconic/1.0.0/font/css/open-iconic.min.css" rel="stylesheet">
  <link href="https://cdn.bootcdn.net/ajax/libs/open-iconic/1.0.0/font/css/open-iconic-bootstrap.min.css" rel="stylesheet">
  <!-- title旁边的icon和ios设备添加到桌面的图标 -->
  <link rel="icon" href="../images/ico.jpg" type="image/x-icon" />
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../images/ico.jpg">
  <link rel="apple-touch-icon-precomposed" sizes="120x120" href="../images/ico.jpg">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../images/ico.jpg">
  <link rel="apple-touch-icon-precomposed" href="../images/ico.jpg">
</head>
<body>

<?php
session_start();
if(isset($_SESSION["tbktools.admin"])){

  // 引入数据库配置
  include '../Db_Connect.php';

  // 连接数据库
  $conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
  mysqli_query($conn, "SET NAMES UTF-8");

  // 获得落地页域名列表
  $sql_yuming = "SELECT * FROM tbk_yuming ORDER BY ID DESC";
  $result_yuming = $conn->query($sql_yuming);

  echo '<div class="container">
    <h2 class="big-title">淘宝客工具箱 - 落地页域名设置</h2>
    <p class="tips">本面板提供落地页域名设置，需要提前做好域名解析，格式：https://www.likeyun.cn，不能以"/"结束。</p>
    <ul class="nav nav-pills" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="pill" href="#home">落地页域名</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="Add-yuming.php">添加域名</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="Pid-Set.php">返回上一页</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php">返回首页</a>
      </li>
    </ul><br/>';

    if ($result_yuming->num_rows > 0) {
      while($row_yuming = $result_yuming->fetch_assoc()) {
        // 域名列表
        $yuming = $row_yuming["yuming"];
        $yumingid = $row_yuming["id"];

        echo '<div class="card">
        <div class="card-body">
        <h4 class="card-title">'.$yuming.'</h4>
        <a href="javascript:;" class="card-link" id="'.$yumingid.'" onclick="GetYumingId(this);" data-toggle="modal" data-target="#delyuming_model">删除</a>
        </div>
        </div>
        <br/>';
      }
    }else{
      echo "暂无域名，<a href='Add-yuming.php'>点击添加</a>";
    }
    echo '</div>';
}else{
  echo header('Location:../admin/Login.php');
}
?>

<!-- 删除域名模态框 -->
<div class="modal fade" id="delyuming_model">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <!-- 模态框头部 -->
      <div class="modal-header">
        <h4 class="modal-title">删除域名</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- 模态框主体 -->
      <div class="delyumingbody modal-body">确定要删除吗？</div>
      <!-- 模态框底部 -->
      <div class="delyuming_footer modal-footer"></div>
    </div>
  </div>
</div>

<script type="text/javascript">
// 获取域名的的yumingid
function GetYumingId(event){
  var YumingId = event.id;
    $("#delyuming_model .modal-dialog .modal-footer").html("<button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\" id="+YumingId+" onclick=\"DelYuming(this);\">确定删除</button>");
}

// 删除域名
function DelYuming(event){
  var DelYumingId = event.id;
  $.ajax({
      type: "GET",
      url: "Del-Yuming.php?yumingid="+DelYumingId,
      success: function (data) {
        if (data.result == "100") {
          location.reload();
        }else{
          alert("未知错误");
        }
      },
      error : function() {
        alert("服务器发生错误");
      }
  });
}
</script>
</body>
</html>