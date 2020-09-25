<!DOCTYPE html>
<html>
<head>
  <title>淘宝客工具箱 - 添加短网址API</title>
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

  echo '<div class="container">
      <h2 class="big-title">淘宝客工具箱 - 添加短网址API</h2>
      <p class="tips">请按照规定的格式开发你的短网址API并填写API</p>
      <!-- 导航栏 -->
      <ul class="nav nav-pills" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" data-toggle="pill" href="#home">添加API</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Dwz-Set.php">返回上一页</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php">返回首页</a>
        </li>
      </ul>
      
      <br/>
      <!-- 表单 -->
      <form role="form" action="##" onsubmit="return false" method="post" id="Adddwzapi">
        <!-- 短网址标题 -->
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text">短网址标题</span>
          </div>
          <input type="text" name="dwztitle" class="form-control" placeholder="请输入短网址标题">
        </div>
        <!-- 短网址API  -->
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text">API URL</span>
          </div>
          <input type="text" name="dwzapi" class="form-control" placeholder="请按照格式输入API链接">
        </div>

        <!-- 设置提交按钮 -->
        <button type="button" class="btn btn-dark" id="autozjy_tkl_btn" onclick="Adddwzapi();">立即添加</button>
      </form>
      
      <!-- 操作提示 -->
      <div class="Result"></div>

      <!-- 要求 -->
      <br/>
      <div class="alert alert-secondary">如需添加短网址API，有开发能力的朋友，请自行开发，要求返回JSON格式：{"result":"100","msg":"解析成功","dwz":"https:\/\/dwz.mk\/yIzQJb"}，其中result为状态码，100代表正常，其他状态码可以自己定，msg为状态码提示文字，dwz为短网址。<br/><br/>要求Get方式传入长链接，例如：http://www.xxx.com/dwz.php?url=长链接，即在上方API URL只需要填写 http://www.xxx.com/dwz.php?url= 即可。<br/><br/>我已经提供2个短网址API，已经提供了源码，只需简单修改源码的相关参数即可，<a href="https://mp.weixin.qq.com/s/1fXx7hdLaN2L-daKyk2pzA" target="blank">小码短网址</a>和<a href="https://mp.weixin.qq.com/s/GTrnxvjqPAOVviquc6zUIA" target="blank">腾讯w.url.cn短网址</a>，请点击查看配置方法。
      </div>
    </div>';

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
  function Adddwzapi(){
      $.ajax({
          type: "POST",
          url: "Adddwz-do.php",
          data: $('#Adddwzapi').serialize(),
          success: function (data) {
            // 设置成功
            if (data.result == "100") {
              $(".container .Result").css('display','block');
              $(".container .Result").html('<div class="alert alert-success"><strong>'+data.msg+'</strong></div>');
              location.href='Dwz-Set.php';
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