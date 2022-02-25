<!DOCTYPE html>
<html>
<head>
  <title>淘宝客工具箱 - 编辑中间页</title>
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

  // 获得上一页传过来的zjy_id
  $zid = $_GET["zid"];
  
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

  // 全局反馈弹出框
  echo '<div id="Result">弹窗弹窗弹窗</div>';

  echo '<div class="container">
    <h2 class="big-title">淘宝客工具箱 - 编辑中间页</h2>
    <p class="tips">本面板提供中间页的编辑，更新，修改等操作。</p>
    <!-- 导航栏 -->
    <ul class="nav nav-pills" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" href="#" id="quanju_btn_bgcolor">编辑中间页</a>
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

          echo '<!-- 表单 -->
          <form role="form" action="##" onsubmit="return false" method="post" id="EdiZjy">
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
                <span class="input-group-text">原价</span>
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
              <input type="text" value="'.$zjy_cover.'" name="zjy_cover" class="form-control" placeholder="请上传商品主图">
              <div class="input-group-append">
                <span class="input-group-text" data-toggle="modal" data-target="#upload_zhutu_model">上传图片</span>
              </div>
            </div>

            <!-- zid -->
            <input type="text" value="'.$zid.'" name="zid" style="display:none;">';

            if ($zjy_template == 'tp1') {
              echo '<!-- 中间页模板 -->
              <select class="form-control" id="byqun_status" style="margin-bottom:15px;" name="zjy_template">
                <option value="tp1">中间页模板：模板一</option>
                <option value="tp2">模板二</option>
                <option value="tp3">模板三</option>
              </select>';
            } else if ($zjy_template == 'tp2') {
              echo '<!-- 中间页模板 -->
              <select class="form-control" id="byqun_status" style="margin-bottom:15px;" name="zjy_template">
                <option value="tp2">中间页模板：模板二</option>
                <option value="tp1">模板一</option>
                <option value="tp3">模板三</option>
              </select>';
            } else if ($zjy_template == 'tp3') {
              echo '<!-- 中间页模板 -->
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
      <button type="button" class="btn btn-dark" onclick="GetFunWenBenHTMLContent_EdiProject();EdiZjy();" id="quanju_btn_bgcolor">更新中间页</button>
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

  // 获得自定义编辑器的内容
  function GetFunWenBenHTMLContent_EdiProject() {
    var project_text_html = $('#zdy_text_bianjiqi').html();
    $("#zdy_text_bianjiqi_content").val(project_text_html); // 获取编辑器的html格式内容(编辑项目)
  }

  // 关闭提示函数
  function closesctips(){
    $(".container .Result").css('display','none');
    $("#Result").css('display','none');
  }

  // Ajax创建中间页
  function EdiZjy(){
      $.ajax({
          type: "POST",
          url: "Edi-Zjy-do.php",
          data: $('#EdiZjy').serialize(),
          success: function (data) {
            // 创建成功
            if (data.result == "100") {
              $("#Result").css('display','block');
              $("#Result").html(data.msg);
              setTimeout('location.href="index.php"', 1000);
            }else{
              $(".container .Result").css('display','block');
              $(".container .Result").html('<div class="alert alert-danger"><strong>'+data.msg+'</strong></div>');
            }
            // 关闭提示
            setTimeout('closesctips()', 2500);
          },
          error : function(data) {
            // 创建失败
             $("#Result").css('display','block');
             $("#Result").html('服务器发生错误');
             setTimeout('closesctips()', 2500);
          },
        beforeSend:function(data){
          // 正在创建
           $("#Result").css('display','block');
           $("#Result").html('正在更新，请稍等...');
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