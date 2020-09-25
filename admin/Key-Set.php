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
  <script src="../Js/clipboard.min.js"></script>
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

  // 获得AppKey
  $sql_set = "SELECT * FROM tbk_set WHERE Set_Obj = 'zjy_appkey'";
  $result_set = $conn->query($sql_set);

  echo '<div class="container">
    <h2 class="big-title">淘宝客工具箱 - AppKey设置</h2>
    <p class="tips">本面板提供AppKey设置，AppKey请登录授权平台申请。</p>
    <ul class="nav nav-pills" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" href="#">AppKey设置</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="Pid-Set.php">返回上一页</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php">返回首页</a>
      </li>
    </ul><br/>';

    if ($result_set->num_rows > 0) {
      while($row_set = $result_set->fetch_assoc()) {
        // AppKey
        $AppKey = $row_set["Set_Val"];

        echo '<div class="card">
        <div class="card-body">
        <h4 class="card-title">'.$AppKey.'</h4>
        <a href="Edi-Set.php?setobj=key" class="card-link">编辑</a>
        </div>
        </div>
        <br/>';
      }
    }else{
      echo "暂无设置项";
    }
    echo '</div>';
}else{
  echo header('Location:../admin/Login.php');
}
?>
</script>
</body>
</html>