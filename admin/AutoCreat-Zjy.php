<!DOCTYPE html>
<html>
<head>
  <title>淘宝客工具箱 - 自动中间页</title>
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
    <h2 class="big-title">淘宝客工具箱 - 自动中间页</h2>
    <p class="tips">本面板提供淘口令的自动创建，只需要粘贴淘口令就可以自动解析出商品信息。</p>
    <!-- 导航栏 -->
    <ul class="nav nav-pills" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="pill" href="#home">自动中间页</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php">返回首页</a>
      </li>
    </ul>
    
    <br/>
    <!-- 表单 -->
    <form role="form" action="##" onsubmit="return false" method="post" id="AutoCreatZjyTklRead">
      <!-- 淘口令 -->
      <div class="input-group mb-3" id="autozjy_tkl">
        <div class="input-group-prepend">
          <span class="input-group-text">淘口令</span>
        </div>
        <input type="text" name="autozjy_tkl" class="form-control" placeholder="请粘贴淘口令">
      </div>

      <!-- 解析提交按钮 -->
      <button type="button" class="btn btn-dark" id="autozjy_tkl_btn" onclick="TklRead();">解析淘口令</button>
    </form>
      
    <form role="form" action="##" onsubmit="return false" method="post" id="CreatZjy">
    <div class="AutoCreatZjy-Con">
      <!-- 长标题 -->
      <div class="cbt"></div>

      <!-- 短标题 -->
      <div class="dbt"></div>

      <!-- 原价 -->
      <div class="yj"></div>

      <!-- 券后价 -->
      <div class="qhj"></div>

      <!-- 淘口令 -->
      <div class="tkl"></div>

      <!-- 主图 -->
      <div class="zt"></div>';

      // 遍历落地页域名列表
      if ($result_yuming->num_rows > 0) {
        echo '<select class="form-control" style="margin-bottom:15px;" name="zjy_yuming">';
        echo '<option value="">请选择落地页域名</option>';
          echo '<option value="http://'.$_SERVER['HTTP_HOST'].'">http://'.$_SERVER['HTTP_HOST'].'</option>';
        while($row_yuming = $result_yuming->fetch_assoc()) {
          echo '<option value="'.$row_yuming["yuming"].'">'.$row_yuming["yuming"].'</option>';
        }
        echo '</select>';
      }else{
        echo '<select class="form-control" style="margin-bottom:15px;" name="zjy_yuming">';
        echo '<option value="">请选择落地页域名</option>';
        echo '<option value="http://'.$_SERVER['HTTP_HOST'].'">http://'.$_SERVER['HTTP_HOST'].'</option>';
        echo '</select>';
      }

      // 遍历短网址API列表
      if ($result_dwzapi->num_rows > 0) {
        echo '<select class="form-control" style="margin-bottom:15px;" name="zjy_dwzapi">';
        echo '<option value="">请选择短网址API</option>';
        while($row_dwzapi = $result_dwzapi->fetch_assoc()) {
          echo '<option value="'.$row_dwzapi["dwzapi"].'">'.$row_dwzapi["dwztitle"].'</option>';
        }
        echo '</select>';
      }

      echo '<!-- 中间页模板 -->
      <select class="form-control" id="byqun_status" style="margin-bottom:15px;" name="zjy_template">
        <option value="">请选择中间页模板</option>
        <option value="tp1">模板一</option>
        <option value="tp2">模板二</option>
        <option value="tp3">模板三</option>
      </select>

      <!-- 提交 -->
      <button type="button" class="btn btn-dark" onclick="CreatZjy();">立即创建</button>
    </div>
    </form>
    
    <!-- 操作提示 -->
    <div class="Result"></div>
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

  // Ajax解析淘口令
  function TklRead(){
      $.ajax({
          type: "POST",
          url: "AutoCreat-Zjy-TklRead-do.php",
          data: $('#AutoCreatZjyTklRead').serialize(),
          success: function (data) {
            //开始解析
            $(".container .Result").css('display','block');
            $(".container .Result").html('<div class="alert alert-warning"><strong>正在解析，请稍等...</strong></div>');
            
            // 解析成功
            if (data.result == "100") {
              $(".container .Result").css('display','block');
              $(".container .Result").html('<div class="alert alert-success"><strong>解析成功</strong></div>');
              // 解析成功，隐藏组件
              $("#autozjy_tkl").css("display","none");
              $("#autozjy_tkl_btn").css("display","none");
              // 显示解析后的商品信息
              $(".container .AutoCreatZjy-Con").css("display","block");
              $(".container .AutoCreatZjy-Con .cbt").html("<div class=\"input-group mb-3\"><div class=\"input-group-prepend\"><span class=\"input-group-text\">长标题</span></div><input type=\"text\" name=\"zjy_long_title\" value="+data.goods_msg.zjy_long_title+" class=\"form-control\" placeholder=\"请输入长标题\"></div>");
              $(".container .AutoCreatZjy-Con .dbt").html("<div class=\"input-group mb-3\"><div class=\"input-group-prepend\"><span class=\"input-group-text\">短标题</span></div><input type=\"text\" name=\"zjy_short_title\" value="+data.goods_msg.zjy_short_title+" class=\"form-control\" placeholder=\"请输入短标题\"></div>");
              $(".container .AutoCreatZjy-Con .yj").html("<div class=\"input-group mb-3\"><div class=\"input-group-prepend\"><span class=\"input-group-text\">原价</span></div><input type=\"text\" name=\"zjy_yprice\" value="+data.goods_msg.zjy_yprice+" class=\"form-control\" placeholder=\"请输入原价\"></div>");
              $(".container .AutoCreatZjy-Con .qhj").html("<div class=\"input-group mb-3\"><div class=\"input-group-prepend\"><span class=\"input-group-text\">券后价</span></div><input type=\"text\" name=\"zjy_qhprice\" value="+data.goods_msg.zjy_qhprice+" class=\"form-control\" placeholder=\"请输入券后价\"></div>");
              $(".container .AutoCreatZjy-Con .tkl").html("<div class=\"input-group mb-3\"><div class=\"input-group-prepend\"><span class=\"input-group-text\">淘口令</span></div><input type=\"text\" name=\"zjy_tkl\" value="+data.goods_msg.zjy_tkl+" class=\"form-control\" placeholder=\"请输入淘口令\"></div>");
              $(".container .AutoCreatZjy-Con .zt").html("<div class=\"input-group mb-3\"><div class=\"input-group-prepend\"><span class=\"input-group-text\">主图</span></div><input type=\"text\" name=\"zjy_cover\" value="+data.goods_msg.zjy_cover+" class=\"form-control\" placeholder=\"请输入主图\"></div>");
            }else{
              $(".container .Result").css('display','block');
              $(".container .Result").html('<div class="alert alert-danger"><strong>'+data.msg+'</strong></div>');
            }
          },
          error : function(data) {
            // 解析失败
            $(".container .Result").css('display','block');
            $(".container .Result").html('<div class="alert alert-danger"><strong>服务器错误，请检查配置文件'+data[0]+'</strong></div>');
          }
      });
      // 关闭提示
      setTimeout('closesctips()', 2000);
  }

  // Ajax创建中间页
  function CreatZjy(){
      $.ajax({
          type: "POST",
          url: "Creat-Zjy-do.php",
          data: $('#CreatZjy').serialize(),
          success: function (data) {
            // 创建成功
            if (data.result == "100") {
               $(".container .Result").css('display','block');
               $(".container .Result").html('<div class="alert alert-success"><strong>'+data.msg+'</strong></div>');
               location.href='index.php';
            }else{
               $(".container .Result").css('display','block');
               $(".container .Result").html('<div class="alert alert-danger"><strong>'+data.msg+'</strong></div>');
            }
            // 关闭提示
            setTimeout('closesctips()', 2000);
          },
          error : function(data) {
            // 创建失败
             $(".container .Result").css('display','block');
             $(".container .Result").html('<div class="alert alert-danger"><strong>服务器发生错误</strong></div>');
             setTimeout('closesctips()', 2000);
          },
          beforeSend : function(data) {
            $(".container .Result").css('display','block');
            $(".container .Result").html('<div class="alert alert-warning"><strong>正在创建，请稍等...</strong></div>');
          }
      });
  }
</script>
</body>
</html>