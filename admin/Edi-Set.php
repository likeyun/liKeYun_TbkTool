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

  // 获得设置对象
  $setobj = trim($_GET["setobj"]);

  // 引入数据库配置
  include '../Db_Connect.php';

  // 连接数据库
  $conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
  mysqli_query($conn, "SET NAMES UTF-8");

  // 获得落地页域名列表
  $sql_yuming = "SELECT * FROM tbk_yuming";
  $result_yuming = $conn->query($sql_yuming);

  // 获得短网址API列表
  $sql_dwzapi = "SELECT * FROM tbk_dwzapi";
  $result_dwzapi = $conn->query($sql_dwzapi);

  // 渲染设置数据
  if ($setobj == 'pid') {
    echo '<div class="container">
      <h2 class="big-title">淘宝客工具箱 - 设置PID</h2>
      <p class="tips">本面板提供PID的设置，必须与授权的一致。</p>
      <!-- 导航栏 -->
      <ul class="nav nav-pills" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" data-toggle="pill" href="#home">设置PID</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Pid-Set.php">返回上一页</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php">返回首页</a>
        </li>
      </ul>
      
      <br/>
      <!-- 表单 -->
      <form role="form" action="##" onsubmit="return false" method="post" id="Set">
        <!-- PID -->
        <div class="input-group mb-3" id="autozjy_tkl">
          <div class="input-group-prepend">
            <span class="input-group-text">PID</span>
          </div>
          <input type="text" name="set_pid" class="form-control" placeholder="请粘贴PID">
          <input type="hidden" name="setobj" value="pid">
        </div>

        <!-- 设置提交按钮 -->
        <button type="button" class="btn btn-dark" id="autozjy_tkl_btn" onclick="SetDo();">立即设置</button>
      </form>
      
      <!-- 操作提示 -->
      <div class="Result"></div>
    </div>';
  }else if($setobj == 'tbname'){
    echo '<div class="container">
      <h2 class="big-title">淘宝客工具箱 - 设置淘宝账号</h2>
      <p class="tips">本面板提供淘宝账号的设置，必须与授权的一致。</p>
      <!-- 导航栏 -->
      <ul class="nav nav-pills" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" data-toggle="pill" href="#home">设置淘宝账号</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Pid-Set.php">返回上一页</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php">返回首页</a>
        </li>
      </ul>
      
      <br/>
      <!-- 表单 -->
      <form role="form" action="##" onsubmit="return false" method="post" id="Set">
        <!-- PID -->
        <div class="input-group mb-3" id="autozjy_tkl">
          <div class="input-group-prepend">
            <span class="input-group-text">淘宝账号</span>
          </div>
          <input type="text" name="set_tbname" class="form-control" placeholder="请填写淘宝账号">
          <input type="hidden" name="setobj" value="tbname">
        </div>

        <!-- 设置提交按钮 -->
        <button type="button" class="btn btn-dark" id="autozjy_tkl_btn" onclick="SetDo();">立即设置</button>
      </form>
      
      <!-- 操作提示 -->
      <div class="Result"></div>
    </div>';
  }else if($setobj == 'key'){
    echo '<div class="container">
      <h2 class="big-title">淘宝客工具箱 - 设置AppKey</h2>
      <p class="tips">本面板提供AppKey设置，AppKey请登录授权平台申请。</p>
      <!-- 导航栏 -->
      <ul class="nav nav-pills" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" data-toggle="pill" href="#home">设置AppKey</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Pid-Set.php">返回上一页</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php">返回首页</a>
        </li>
      </ul>
      
      <br/>
      <!-- 表单 -->
      <form role="form" action="##" onsubmit="return false" method="post" id="Set">
        <!-- PID -->
        <div class="input-group mb-3" id="autozjy_tkl">
          <div class="input-group-prepend">
            <span class="input-group-text">AppKey</span>
          </div>
          <input type="text" name="set_appkey" class="form-control" placeholder="请填写AppKey">
          <input type="hidden" name="setobj" value="appkey">
        </div>

        <!-- 设置提交按钮 -->
        <button type="button" class="btn btn-dark" id="autozjy_tkl_btn" onclick="SetDo();">立即设置</button>
      </form>
      
      <!-- 操作提示 -->
      <div class="Result"></div>
    </div>';
  }

}else{
  echo header('Location:../admin/Login.php');
}
?>

<!-- Ajax -->
<script type="text/javascript">
  // 关闭提示函数
  function closesctips(){
    $(".container .Result").css('display','none');
  }

  // Ajax提交设置
  function SetDo(){
      $.ajax({
          type: "POST",
          url: "Set-do.php",
          data: $('#Set').serialize(),
          success: function (data) {
            // 设置成功
            if (data.result == "100") {
              $(".container .Result").css('display','block');
              $(".container .Result").html('<div class="alert alert-success"><strong>'+data.msg+'</strong></div>');
              location.href='Pid-Set.php';
            }else{
              $(".container .Result").css('display','block');
              $(".container .Result").html('<div class="alert alert-danger"><strong>'+data.msg+'</strong></div>');
            }
            
            // 关闭提示
            setTimeout('closesctips()', 2000);
          },
          error : function(data) {
            // 设置失败
            $(".container .Result").css('display','block');
            $(".container .Result").html('<div class="alert alert-danger"><strong>服务器错误，请检查配置文件</strong></div>');

            // 关闭提示
            setTimeout('closesctips()', 2000);
          },
          beforeSend : function(data) {
            $(".container .Result").html('<div class="alert alert-warning"><strong>正在设置，请稍等...</strong></div>');
          }
      });
  }
</script>
</body>
</html>