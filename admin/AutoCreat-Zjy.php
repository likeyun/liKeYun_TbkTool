<!DOCTYPE html>
<html>
<head>
  <title>淘宝客工具箱 - 自动中间页</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://cdn.staticfile.org/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdn.staticfile.org/popper.js/1.15.0/umd/popper.min.js"></script>
  <script src="../js/tinymce.min.js"></script>
  <script src="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <!-- title旁边的icon和ios设备添加到桌面的图标 -->
  <link rel="icon" href="../images/ico.jpg" type="image/x-icon" />
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../images/ico.jpg">
  <link rel="apple-touch-icon-precomposed" sizes="120x120" href="../images/ico.jpg">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../images/ico.jpg">
  <link rel="apple-touch-icon-precomposed" href="../images/ico.jpg">
  <script>
    tinymce.init({
      selector: '#mytextarea'
    });
  </script>
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

  // 全局反馈弹出框
  echo '<div id="Result">弹窗弹窗弹窗</div>';

  echo '<div class="container">
    <h2 class="big-title">淘宝客工具箱 - 创建中间页</h2>
    <p class="tips">本面板提供淘口令的自动创建，只需要粘贴淘口令就可以自动解析出商品信息。</p>
    <!-- 导航栏 -->
    <ul class="nav nav-pills" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="pill" href="#home" id="quanju_btn_bgcolor">快速创建</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php">返回首页</a>
      </li>
    </ul>
    
    <br/>
    <!-- 表单 -->
    <form role="form" action="##" onsubmit="return false" method="post" id="AutoCreatZjyTklRead">
      <!-- 淘口令 -->
      <textarea name="autozjy_tkl" style="width:100%;-webkit-appearance:none;border:2px solid #333;border-radius:5px;outline:none;font-size:16px;height:120px;margin-bottom:12px;padding:15px 15px;" placeholder="请粘贴淘口令或含有淘口令的文案" id="autozjy_tkl"></textarea>

      <!-- 解析提交按钮 -->
      <button type="button" class="btn btn-dark" id="autozjy_tkl_btn" style="background: linear-gradient(to bottom right, #ed773c , #ea4e44);border:none;" onclick="TklRead();">解析淘口令</button>
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

      <!-- 商品图 -->
      <div class="zt"></div>';

      // 遍历落地页域名列表
      if ($result_yuming->num_rows > 0) {
        echo '<select class="form-control" style="margin-bottom:15px;" name="zjy_yuming">';
          echo '<option value="http://'.$_SERVER['HTTP_HOST'].'">落地页域名：'.$_SERVER['HTTP_HOST'].'</option>';
        while($row_yuming = $result_yuming->fetch_assoc()) {
          echo '<option value="'.$row_yuming["yuming"].'">'.$row_yuming["yuming"].'</option>';
        }
        echo '</select>';
      }else{
        echo '<select class="form-control" style="margin-bottom:15px;" name="zjy_yuming">';
        echo '<option value="http://'.$_SERVER['HTTP_HOST'].'">落地页域名：'.$_SERVER['HTTP_HOST'].'</option>';
        echo '</select>';
      }

      // 遍历短网址API列表
      if ($result_dwzapi->num_rows > 0) {
        echo '<select class="form-control" style="margin-bottom:15px;" name="zjy_dwzapi">';
        echo '<option value="">请选择短网址API</option>';
        echo '<option value="暂无生成">暂不生成短网址</option>';
        while($row_dwzapi = $result_dwzapi->fetch_assoc()) {
          echo '<option value="'.$row_dwzapi["dwzapi"].'">'.$row_dwzapi["dwztitle"].'</option>';
        }
        echo '</select>';
      }

      echo '<!-- 中间页模板 -->
      <select class="form-control" id="byqun_status" style="margin-bottom:15px;" name="zjy_template">
        <option value="tp1">模板一</option>
        <option value="tp2">模板二</option>
        <option value="tp3">模板三</option>
      </select>

      <!-- 提交 -->
      <button type="button" class="btn btn-dark" id="quanju_btn_bgcolor" onclick="GetFunWenBenHTMLContent();CreatZjy();">立即创建</button>
    </div>
    </form>
    
    <!-- 操作提示 -->
    <div class="Result"></div>
    <br/><br/><br/>
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
    $("#Result").css('display','none');
  }

  // Ajax解析淘口令
  function TklRead(){
      $.ajax({
          type: "POST",
          url: "AutoCreat-Zjy-TklRead-do.php",
          data: $('#AutoCreatZjyTklRead').serialize(),
          success: function (data) {
            //开始解析
            $("#Result").css('display','block');
            $("#Result").text('正在解析，请稍等...');
            
            // 解析成功
            if (data.result == "100") {
              $("#Result").css('display','block');
              $("#Result").text('解析成功');
              // 解析成功，隐藏组件
              $("#autozjy_tkl").css("display","none");
              $("#autozjy_tkl_btn").css("display","none");
              // 显示解析后的商品信息
              $(".container .AutoCreatZjy-Con").css("display","block");
              $(".container .AutoCreatZjy-Con .cbt").html("<div class=\"input-group mb-3\"><div class=\"input-group-prepend\"><span class=\"input-group-text\">长标题</span></div><input type=\"text\" name=\"zjy_long_title\" value="+data.goods_msg.zjy_long_title+" class=\"form-control\" placeholder=\"请输入长标题\"></div>");
              $(".container .AutoCreatZjy-Con .dbt").html("<div class=\"input-group mb-3\"><div class=\"input-group-prepend\"><span class=\"input-group-text\">短标题</span></div><input type=\"text\" name=\"zjy_short_title\" value="+data.goods_msg.zjy_short_title+" class=\"form-control\" placeholder=\"请输入短标题\"></div>");
              $(".container .AutoCreatZjy-Con .yj").html("<div class=\"input-group mb-3\"><div class=\"input-group-prepend\"><span class=\"input-group-text\">原售价</span></div><input type=\"text\" name=\"zjy_yprice\" value="+data.goods_msg.zjy_yprice+" class=\"form-control\" placeholder=\"请输入原价\"></div>");
              $(".container .AutoCreatZjy-Con .qhj").html("<div class=\"input-group mb-3\"><div class=\"input-group-prepend\"><span class=\"input-group-text\">券后价</span></div><input type=\"text\" name=\"zjy_qhprice\" value="+data.goods_msg.zjy_qhprice+" class=\"form-control\" placeholder=\"请输入券后价\"></div>");
              $(".container .AutoCreatZjy-Con .tkl").html("<div class=\"input-group mb-3\"><div class=\"input-group-prepend\"><span class=\"input-group-text\">淘口令</span></div><input type=\"text\" name=\"zjy_tkl\" value="+data.goods_msg.zjy_tkl+" class=\"form-control\" placeholder=\"请输入淘口令\"></div>");
              $(".container .AutoCreatZjy-Con .zt").html("<div class=\"input-group mb-3\"><div class=\"input-group-prepend\"><span class=\"input-group-text\">商品图</span></div><input type=\"text\" name=\"zjy_cover\" value="+data.goods_msg.zjy_cover+" class=\"form-control\" placeholder=\"请粘贴商品图\"></div>");
              $(".container .AutoCreatZjy-Con .gdydwa_1").html("<div class=\"input-group mb-3\"><div class=\"input-group-prepend\"><span class=\"input-group-text\">固定引导文案①</span></div><input type=\"text\" name=\"gdydwa_1\" value=\"①点进下方链接，复制口令\" class=\"form-control\" placeholder=\"请输入引导文案\"></div>");
              $(".container .AutoCreatZjy-Con .gdydwa_2").html("<div class=\"input-group mb-3\"><div class=\"input-group-prepend\"><span class=\"input-group-text\">固定引导文案②</span></div><input type=\"text\" name=\"gdydwa_2\" value=\"②打开手机TAO宝，领券下单\" class=\"form-control\" placeholder=\"请输入引导文案\"></div>");
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
      setTimeout('closesctips()', 2500);
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
              $("#Result").css('display','block');
              $("#Result").html(data.msg);
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
            $("#Result").css('display','block');
            $("#Result").html('服务器发生错误');
            setTimeout('closesctips()', 2000);
          },
          beforeSend : function(data) {
            $("#Result").css('display','block');
            $("#Result").html('正在创建，请稍等...');
          }
      });
  }

  // 获取富文本编辑器的HTML格式的内容(添加项目)
  function GetFunWenBenHTMLContent() {
    var active_text_html = $('#zdy_text_bianjiqi').html();
    $("#zdy_text_bianjiqi_content").val(active_text_html); // 获取编辑器的html格式内容
  }
</script>
</body>
</html>