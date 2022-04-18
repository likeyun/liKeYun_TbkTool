<!DOCTYPE html>
<html>
<head>
  <title>淘宝客工具箱 - 编辑中间页</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="../js/jquery.min.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/clipboard.min.js"></script>
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/style.css">
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

  // 获得上一页传过来的zjy_id
  $zid = trim($_GET["zid"]);
  $token = trim($_GET["token"]);
  if(empty($zid) || empty($token) || $token == '' || !isset($token) || $token !== md5($zid)){
      echo '<p style="text-align:center;margin-top:200px;font-size:50px;">非法请求</p><img src="../images/hack.png" style="margin:10px auto;display:block;" />';
      exit;
  }
  
  // 引入数据库配置
  include '../Db_Connect.php';

  // 连接数据库
  $conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
  mysqli_query($conn, "SET NAMES UTF-8");

  // 获得落地页域名列表
  $sql_yuming = "SELECT * FROM tbk_yuming";
  $result_yuming = $conn->query($sql_yuming);

  // 获得中间页详情
  $sql_zjyMsg = "SELECT * FROM tbk_zjy WHERE zjy_id = ".$zid;
  $result_zjyMsg = $conn->query($sql_zjyMsg);

  echo '<div class="container">
    <h2 class="big-title">淘宝客工具箱 - 编辑中间页</h2>
    <p class="tips">中间页编辑，更新，修改等操作。</p>
    <!-- 导航栏 -->
    <ul class="nav nav-pills" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" href="#">编辑</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php">返回首页</a>
      </li>
    </ul><br/>';
    
      if ($result_zjyMsg->num_rows > 0) {
        while($row_zjyMsg = $result_zjyMsg->fetch_assoc()) {
          $zid = $row_zjyMsg["zjy_id"];
          $zjy_long_title = $row_zjyMsg["zjy_long_title"];
          $zjy_short_title = $row_zjyMsg["zjy_short_title"];
          $zjy_yprice = $row_zjyMsg["zjy_yprice"];
          $zjy_qhprice = $row_zjyMsg["zjy_qhprice"];
          $zjy_cover = $row_zjyMsg["zjy_cover"];
          $zjy_template = $row_zjyMsg["zjy_template"];
          $zjy_dwz = $row_zjyMsg["zjy_dwz"];
          $zjy_pv = $row_zjyMsg["zjy_pv"];
          $zjy_tkl = $row_zjyMsg["zjy_tkl"];
          $zjy_tkl = $row_zjyMsg["zjy_tkl"];

          echo '<!-- 表单 -->
          <form role="form" onsubmit="return false" method="post" id="EdiZjy" enctype="multipart/form-data">
            <!-- 长标题 -->
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">长标题</span>
              </div>
              <input type="text" value="'.$zjy_long_title.'" name="zjy_long_title" class="form-control" placeholder="请输入长标题">
            </div>

            <!-- 短标题 -->
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">短标题</span>
              </div>
              <input type="text" value="'.$zjy_short_title.'" name="zjy_short_title" class="form-control" placeholder="请输入短标题">
            </div>

            <!-- 原价 -->
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">原售价</span>
              </div>
              <input type="text" value="'.$zjy_yprice.'" name="zjy_yprice" class="form-control" placeholder="请输入原价，例如39.90">
            </div>

            <!-- 券后价 -->
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">券后价</span>
              </div>
              <input type="text" value="'.$zjy_qhprice.'" name="zjy_qhprice" class="form-control" placeholder="请输入券后价，例如9.90">
            </div>

            <!-- 淘口令 -->
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">淘口令</span>
              </div>
              <input type="text" value="'.$zjy_tkl.'" name="zjy_tkl" class="form-control" placeholder="请输入淘口令">
            </div>

            <!-- 主图 -->
            <div class="input-group mb-3" id="zhutu">
              <input type="text" value="'.$zjy_cover.'" name="zjy_cover" class="form-control zhutuimg" placeholder="请上传商品主图或粘贴主图链接">
              <div class="input-group-append upload-click">
                <span class="input-group-text">上传图片</span>
                <input type="file" name="file" class="file" id="imgselect" />
              </div>
            </div>

            <!-- zid -->
            <input type="text" value="'.$zid.'" name="zid" style="display:none;">';

            if ($zjy_template == 'tp1') {
              echo '
              <!-- 中间页模板1 -->
              <select class="form-control" id="byqun_status" style="margin-bottom:15px;" name="zjy_template">
                <option value="tp1">中间页模板：模板一</option>
                <option value="tp2">模板二</option>
                <option value="tp3">模板三</option>
              </select>';
            } else if ($zjy_template == 'tp2') {
              echo '
              <!-- 中间页模2板 -->
              <select class="form-control" id="byqun_status" style="margin-bottom:15px;" name="zjy_template">
                <option value="tp2">中间页模板：模板二</option>
                <option value="tp1">模板一</option>
                <option value="tp3">模板三</option>
              </select>';
            } else if ($zjy_template == 'tp3') {
              echo '
              <!-- 中间页模板3 -->
              <select class="form-control" id="byqun_status" style="margin-bottom:15px;" name="zjy_template">
                <option value="tp3">中间页模板：模板三</option>
                <option value="tp1">模板一</option>
                <option value="tp2">模板二</option>
              </select>';
            }
        }
      }else{
        echo "参数错误";
      }

      echo '<!-- 提交 -->
      <button type="button" class="btn-zdy" onclick="EdiZjy();">提交更新</button>
    </form>

    <!-- 处理结果 -->
    <div class="Result"></div>

  </div>';
}else{
  echo header('Location:../admin/Login.php');
}
?>

<script type="text/javascript">

  // 关闭提示函数
  function closesctips(){
    $(".container .Result").css('display','none');
  }

  // 编辑中间页
  function EdiZjy(){
      $.ajax({
          type: "POST",
          url: "Edi-Zjy-do.php",
          data: $('#EdiZjy').serialize(),
          success: function (data) {
            
            // 编辑成功
            if (data.result == "100") {
               $(".container .Result").css('display','block');
               $(".container .Result").html('<div class="alert alert-success"><strong>'+data.msg+'</strong></div>');
               location.href='index.php';
            }else{
               $(".container .Result").css('display','block');
               $(".container .Result").html('<div class="alert alert-danger"><strong>'+data.msg+'</strong></div>');
            }
          },
          error : function(data) {
            
            // 创建失败
            $(".container .Result").css('display','block');
            $(".container .Result").html('<div class="alert alert-danger"><strong>服务器发生错误</strong></div>');
            setTimeout('closesctips()', 2000);
          },
        beforeSend:function(data){
           $(".container .Result").css('display','block');
           $(".container .Result").html('<div class="alert alert-danger"><strong>正在更新，请稍等</strong></div>');
        }
      });
  }

    // 上传主图
    var zhutu_lunxun = setInterval("upload_zhutu()",2000);
    function upload_zhutu() {
    var zhutu_filename = $("#imgselect").val();
    if (zhutu_filename) {
      clearInterval(zhutu_lunxun);
      var zhutuform = new FormData(document.getElementById("EdiZjy"));
      $.ajax({
        url:"upload.php",
        type:"post",
        data:zhutuform,
        cache: false,
        processData: false,
        contentType: false,
        success:function(data){
          if (data.res == "400") {
            $("#zhutu .input-group-text").text('已上传');
            $("#zhutu .zhutuimg").val(data.path);
            $(".container .Result").css('display','block');
            $(".container .Result").html('<div class="alert alert-success"><strong>上传成功</strong></div>');
            // 关闭提示
            setTimeout('closesctips()', 2000);
          }else{
            $(".container .Result").css('display','block');
            $(".container .Result").html('<div class="alert alert-danger"><strong>上传失败，失败原因：1文件类型不支持，2文件大小超限。如需上传，请刷新页面重试。</strong></div>');
            $("#zhutu .input-group-text").text('上传失败');
          }
        },
        error:function(data){
          $(".container .Result").css('display','block');
          $(".container .Result").html('<div class="alert alert-danger"><strong>上传失败，请检查服务器及upload.php是否存在服务错误，可F12打开开发者工具选择NetWork->Preview查看网络请求进行排查。</strong></div>');
        },
        beforeSend:function(data){
          $("#zhutu .input-group-text").text('正在上传..');
          $(".container .Result").css('display','block');
          $(".container .Result").html('<div class="alert alert-danger"><strong>正在上传...</strong></div>');
        }
      })
    }else{
    //   $(".container .Result").css('display','block');
    //   $(".container .Result").html('<div class="alert alert-danger"><strong>等待上传...</strong></div>');
    }
  }
</script>
</body>
</html>