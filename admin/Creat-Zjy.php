<!DOCTYPE html>
<html>
<head>
  <title>淘宝客工具箱 - 创建中间页</title>
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
  <style type="text/css">
    #Result_Succes{
      width: 200px;
      height: 80px;
      background: #000;
      position: fixed;
      top: 280px;
      left: 0;
      right: 0;
      margin:0 auto;
      z-index: 9999999999999;
      opacity: 0.5;
      border-radius: 10px;
      text-align: center;
      line-height: 80px;
      color: #fff;
      font-size: 17px;
      /*font-weight: bold;*/
      display: none;
    }
  </style>
</head>
<body>

<?php
error_reporting(E_ALL ^ E_DEPRECATED);
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

  echo '<div id="Result_Succes">创建成功</div>';

  echo '<div class="container">
    <h2 class="big-title">淘宝客工具箱 - 创建中间页链接</h2>
    <p class="tips">根据淘口令解析出来的结果，进行转换淘口令，自动创建中间页。</p>
    <!-- 导航栏 -->
    <ul class="nav nav-pills" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="pill" href="#home">创建链接</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php">返回首页</a>
      </li>
    </ul>
    
    <br/>
    <!-- 表单 -->
    <form role="form" action="##" onsubmit="return false" method="post" id="AutoCreatZjyTklRead">
      <!-- 淘口令 -->
      <textarea name="autozjy_tkl" style="width:100%;-webkit-appearance:none;border:1px solid #666;border-radius:5px;outline:none;font-size:16px;height:120px;margin-bottom:12px;padding:15px 15px;" placeholder="请粘贴淘口令或含有淘口令的文案" id="autozjy_tkl"></textarea>

      <!-- 解析提交按钮 -->
      <button type="button" class="btn-zdy" id="autozjy_tkl_btn" onclick="TklRead();">解析淘口令</button>
    </form>
      
    <form role="form" action="##" onsubmit="return false" method="post" id="CreatZjy">
    <div class="AutoCreatZjy-Con">
    
        <!-- 长标题 -->
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">长标题</span>
            </div>
            <input type="text" name="zjy_long_title" id="cbt" class="form-control" placeholder="请输入长标题">
        </div>

        <!-- 短标题 -->
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">短标题</span>
            </div>
            <input type="text" name="zjy_short_title" id="dbt" class="form-control" placeholder="请输入长标题">
        </div>

        <!-- 原价 -->
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">原售价</span>
            </div>
            <input type="text" name="zjy_yprice" id="yj" class="form-control" placeholder="请输入原价">
        </div>

        <!-- 原价 -->
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">券后价</span>
            </div>
            <input type="text" name="zjy_qhprice" id="qhj" class="form-control" placeholder="请输入券后价">
        </div>

        <!-- 淘口令 -->
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">淘口令</span>
            </div>
            <input type="text" name="zjy_tkl" id="tkl" class="form-control" placeholder="请粘贴淘口令">
        </div>

        <!-- 主图 -->
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">商品图</span>
            </div>
            <input type="text" name="zjy_cover" id="zt" class="form-control" placeholder="请粘贴商品图链接">
        </div>';

      // 落地页域名列表
      if ($result_yuming->num_rows > 0) {
          echo '<select class="form-control" style="margin-bottom:15px;" name="zjy_yuming">';
          // 将当前管理后台的域名添加到落地域名
          echo '<option value="'.$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'">http://'.$_SERVER['HTTP_HOST'].'</option>';
          // 获取服务器中自己添加的落地域名
          while($row_yuming = $result_yuming->fetch_assoc()) {
              echo '<option value="'.$row_yuming["yuming"].'">'.$row_yuming["yuming"].'</option>';
          }
          echo '</select>';
      }else{
        echo '<select class="form-control" style="margin-bottom:15px;" name="zjy_yuming">';
        // 将当前管理后台的域名添加到落地域名
        echo '<option value="'.$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'">http://'.$_SERVER['HTTP_HOST'].'</option>';
        echo '</select>';
      }

      // 短网址API列表
      echo '
      <select class="form-control" style="margin-bottom:15px;" name="zjy_dwzapi">
      <option value="2">本地短网址</option>
      <option value="1">不生成短网址</option>
      </select>
      ';
      

      // 中间页模板列表
      echo '
      <select class="form-control" id="byqun_status" style="margin-bottom:15px;" name="zjy_template">
      <option value="tp1">模板一</option>
      <option value="tp2">模板二</option>
      <option value="tp3">模板三</option>
      </select>
      <!-- 微信跳转APP链接 -->
      <input type="hidden" name="AppRedUrl" id="AppRedUrl" />

      <!-- 提交 -->
      <button type="button" class="btn-zdy" onclick="CreatZjy();">立即创建</button>
    </div>
    </form>
    
    <!-- 操作提示 -->
    <div class="Result"></div>';

}else{
  echo header('Location:../admin/Login.php');
}
?>

<!-- Ajax -->
<script type="text/javascript">
  // 关闭提示函数
  function closesctips(){
    $("#Phone_Result").css('display','none');
    $(".container .Result").css('display','none');
  }

  // Ajax解析淘口令
  function TklRead(){
      $.ajax({
          type: "POST",
          url: "Creat-Zjy-TklRead-do.php",
          data: $('#AutoCreatZjyTklRead').serialize(),
          success: function (data) {
              
            //开始解析
            $(".container .Result").css('display','block');
            $("#Phone_Result").css('display','block');
            $(".container .Result").html('<div class="alert alert-warning"><strong>正在解析，请稍等...</strong></div>');
            $("#Phone_Result").html('<div class="alert alert-warning"><strong>正在解析，请稍等...</strong></div>');
            
            // 解析成功
            if (data.result == "100") {
              $(".container .Result").css('display','block');
              $("#Phone_Result").css('display','block');
              $("#Phone_Result").html('<div class="alert alert-success"><strong>解析成功</strong></div>');
              $(".container .Result").html('<div class="alert alert-success"><strong>解析成功</strong></div>');
              // 解析成功，隐藏组件
              $("#autozjy_tkl").css("display","none");
              $("#autozjy_tkl_btn").css("display","none");
              // 显示解析后的商品信息
              $(".container .AutoCreatZjy-Con").css("display","block");
              $("#cbt").val(data.goods_msg.zjy_long_title);
              $("#dbt").val(data.goods_msg.zjy_short_title);
              $("#yj").val(data.goods_msg.zjy_yprice);
              $("#qhj").val(data.goods_msg.zjy_qhprice);
              $("#tkl").val(data.goods_msg.zjy_tkl);
              $("#zt").val(data.goods_msg.zjy_cover);
              $("#AppRedUrl").val(data.goods_msg.AppRedUrl);
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
               $("#Result_Succes").css('display','block');
               setTimeout('location.href="index.php"', 2000);
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
            $(".container .Result").html('<div class="alert alert-danger"><strong>正在创建，请稍等...</strong></div>');
          }
      });
  }
</script>
</body>
</html>