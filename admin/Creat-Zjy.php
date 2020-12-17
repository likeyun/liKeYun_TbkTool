<!DOCTYPE html>
<html>
<head>
  <title>淘宝客工具箱 - 手动创建中间页</title>
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
    .modal .modal-dialog .modal-content .modal-body .btn{
      position: relative;
    }

    .modal .modal-dialog .modal-content .modal-body .file_btn{
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      opacity: 0;
    }
  </style>
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
    <h2 class="big-title">淘宝客工具箱 - 手动创建中间页</h2>
    <p class="tips">本面板提供淘口令的手动创建，需要自己手动输入各项淘口令信息，自定义创建中间页。</p>
    <!-- 导航栏 -->
    <ul class="nav nav-pills" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" href="#里客云科技www.likeyun.cn">手动创建中间页</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php">返回首页</a>
      </li>
    </ul>
    
    <br/>
    <!-- 表单 -->
    <form role="form" action="##" onsubmit="return false" method="post" id="CreatZjy">
      <!-- 长标题 -->
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text">长标题</span>
        </div>
        <input type="text" name="zjy_long_title" class="form-control" placeholder="请输入长标题">
      </div>

      <!-- 短标题 -->
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text">短标题</span>
        </div>
        <input type="text" name="zjy_short_title" class="form-control" placeholder="请输入短标题">
      </div>

      <!-- 原售价 -->
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text">原售价</span>
        </div>
        <input type="text" name="zjy_yprice" class="form-control" placeholder="请输入原价，例如39.90">
      </div>

      <!-- 券后价 -->
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text">券后价</span>
        </div>
        <input type="text" name="zjy_qhprice" class="form-control" placeholder="请输入券后价，例如9.90">
      </div>

      <!-- 淘口令 -->
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text">淘口令</span>
        </div>
        <input type="text" name="zjy_tkl" class="form-control" placeholder="请输入淘口令">
      </div>

      <!-- 主图 -->
      <div class="input-group mb-3" id="zhutu">
        <input type="text" name="zjy_cover" class="form-control" placeholder="请上传商品主图">
        <div class="input-group-append">
          <span class="input-group-text" data-toggle="modal" data-target="#upload_zhutu_model">上传图片</span>
        </div>
      </div>';

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

      // 遍历落地页域名列表
      if ($result_dwzapi->num_rows > 0) {
        echo '<select class="form-control" style="margin-bottom:15px;" name="zjy_dwzapi">';
        echo '<option value="">请选择短网址API</option>';
        echo '<option value="暂无生成">暂不生成短网址</option>';
        while($row_dwzapi = $result_dwzapi->fetch_assoc()) {
          echo '<option value="'.$row_dwzapi["dwzapi"].'">'.$row_dwzapi["dwztitle"].'</option>';
        }
        echo '</select>';
      }else{
        echo '<select class="form-control" style="margin-bottom:15px;" name="zjy_dwzapi">';
        echo '<option value="">请选择短网址API</option>';
        echo '<option value="暂无生成">无短网址API，暂不生成</option>';
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
    </form>

    <!-- 处理结果 -->
    <div class="Result"></div>

  </div>';
}else{
  echo header('Location:../admin/Login.php');
}
?>

<!-- 上传主图 -->
<div class="modal fade" id="upload_zhutu_model">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
 
      <!-- 模态框头部 -->
      <div class="modal-header">
        <h4 class="modal-title">上传主图</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
 
      <!-- 模态框主体 -->
      <div class="zhutubody modal-body">
        <form id="upload_zhutu" enctype="multipart/form-data">
          <button type="button" class="btn btn-dark">
            <input type="file" id="select_zhutu" class="file_btn" name="file"/>
            选择图片
          </button>
        </form>
      </div>
 
      <!-- 模态框底部 -->
      <div class="zhutu_footer modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消上传</button>
      </div>
 
    </div>
  </div>
</div>

<!-- Ajax -->
<script type="text/javascript">
  // 关闭提示函数
  function closesctips(){
    $(".container .Result").css('display','none');
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
        beforeSend:function(data){
          // 正在创建
           $(".container .Result").css('display','block');
           $(".container .Result").html('<div class="alert alert-warning"><strong>正在创建，请稍等</strong></div>');
        }
      });
  }

  // 上传主图
  var zhutu_lunxun = setInterval("upload_zhutu()",2000);
    function upload_zhutu() {
    var zhutu_filename = $("#select_zhutu").val();
    if (zhutu_filename) {
      clearInterval(zhutu_lunxun);
      var zhutuform = new FormData(document.getElementById("upload_zhutu"));
      $.ajax({
        url:"upload.php",
        type:"post",
        data:zhutuform,
        cache: false,
        processData: false,
        contentType: false,
        success:function(data){
          if (data.res == "400") {
            $("#zhutu").html('<input type="text" name="zjy_cover" value="'+data.path+'" class="form-control"><div class="input-group-append"><span class="input-group-text">已上传</span></div>');
            $(".modal .modal-dialog .modal-content .zhutubody").html("<h3>上传成功</h3>");
            $(".modal .modal-dialog .zhutu_footer").html('<button type="button" class="btn btn-secondary" data-dismiss="modal">完成上传</button>');
          }
        },
        error:function(data){
          $(".modal .modal-dialog .modal-content .zhutubody").html("<h3>上传失败，请检查服务器</h3>");
        },
        beforeSend:function(data){
          $(".modal .modal-dialog .modal-content .zhutubody").html("<h3>正在上传...</h3>");
        }
      })
    }else{
      // console.log("等待上传");
    }
  }
</script>
</body>
</html>