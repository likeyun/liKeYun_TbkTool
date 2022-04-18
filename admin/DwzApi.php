<!DOCTYPE html>
<html>
<head>
  <title>淘宝客工具箱 - 配置</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.staticfile.org/popper.js/1.15.0/umd/popper.min.js"></script>
  <script src="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <script src="../js/clipboard.min.js"></script>
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
    
  // 登录用户
  $user = $_SESSION["tbktools.admin"];

  // 引入数据库配置
  include '../Db_Connect.php';

  // 连接数据库
  $conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
  mysqli_query($conn, "SET NAMES UTF-8");

  // 获得落地页域名列表
  $sql_yuming = "SELECT * FROM tbk_yuming ORDER BY ID DESC";
  $result_yuming = $conn->query($sql_yuming);
  
  // 获得用户列表
  $sql_userlist = "SELECT * FROM tbk_user ORDER BY ID DESC";
  $result_userlist = $conn->query($sql_userlist);

  echo '<div class="container">
    <h2 class="big-title">淘宝客工具箱 - 短网址API</h2>
    <p class="tips">配置短网址API的一些说明</p>
    <ul class="nav nav-pills" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="pill" href="#home">短网址API</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="Peizhi.php">返回工具箱配置</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php">返回首页</a>
      </li>
    </ul><br/>';
    
    // 配置教程
    echo '
    <div class="PeiZhiJiaoCheng">
    <p>因短网址API更换频率较低，我们为了精简开发，没有开发后台配置，如需配置，请在源代码中进行配置。具体配置方法如下：<p>
    <p>配置创建中间页时使用的短网址API，编辑Creat-Zjy.php文件找到短网址API配置相关代码片段，增加一个<code>&lt;option value=&quot;你的API接口URL&quot;&gt;API名称&lt;/option&gt;</code>标签。</p>
    <p><img src="../images/duanwangzhipeizhi1.png" /></p>
    <p>例如：<code>&lt;option value=&quot;http://www.xxx.com/api/dwz.php?url=&quot;&gt;API名称&lt;/option&gt;</code></p>
    <p><img src="../images/duanwangzhipeizhi2.png" /></p>
    <p>配置短网址API仅限具有开发能力的开发者，开发的API需返回JSON字符串，其中code为状态码，url为短网址，msg为请求结果描述文字，具体返回的JSON字符串格式如下：</p>
    <p>请求成功：<code>{&quot;code&quot;:&quot;200&quot;,&quot;url&quot;:&quot;http://xxx.xx/xxxx&quot;}</code></p>
    <p>请求失败：<code>{&quot;code&quot;:&quot;201&quot;,&quot;msg&quot;:&quot;生成失败&quot;}</code></p>
    <p>同理，配置创建活动页时使用的短网址API，编辑Creat-ActiveZjy.php文件找到短网址API配置相关代码片段，增加一个<code>&lt;option value=&quot;你的API接口URL&quot;&gt;API名称&lt;/option&gt;</code>标签即可，跟上面配置方法一致。</p>
    </div>';
}else{
  echo header('Location:../admin/Login.php');
}
?>

<!-- 添加域名 -->
<div class="modal fade" id="Add-ym">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <!-- 模态框头部 -->
      <div class="modal-header">
        <h4 class="modal-title">添加域名</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- 模态框主体 -->
      <div class="modal-body">
        <form role="form" onsubmit="return false" id="Addym">
          <div class="form-group">
            <input type="text" name="yuming" class="form-control" placeholder="输入域名">
          </div>
          <button type="submit" class="btn-zdy" onclick="Addym();">确认添加</button>
          <p style="width:100%;height:30px;display:block;text-align:center;color: #666;font-size: 14px;margin-top:20px;">添加前请做好域名解析</p>
          <div id="Result_Tips"></div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- 创建账号 -->
<div class="modal fade" id="Creat-User">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <!-- 模态框头部 -->
      <div class="modal-header">
        <h4 class="modal-title">创建账号</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- 模态框主体 -->
      <div class="modal-body">
        <form role="form" onsubmit="return false" id="Creatuser">
          <div class="form-group">
            <input type="text" name="user_name" class="form-control" placeholder="输入账号">
          </div>
          <div class="form-group">
            <input type="text" name="user_pwd" class="form-control" placeholder="输入密码">
          </div>
          <div class="form-group">
            <input type="text" name="pid" class="form-control" placeholder="粘贴PID">
          </div>
          <div class="form-group">
            <input type="text" name="appkey" class="form-control" placeholder="粘贴AppKey">
          </div>
          <div class="form-group">
            <input type="text" name="user_email" class="form-control" placeholder="输入邮箱">
          </div>
          <div class="form-group">
            <input type="text" name="tbname" class="form-control" placeholder="输入淘宝账号">
          </div>
          <button type="submit" class="btn-zdy" onclick="Creatuser();">确认创建</button>
          <div class="Result_Tips"></div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- 删除活动 -->
<div class="modal fade" id="delzjy_model">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <!-- 模态框头部 -->
      <div class="modal-header">
        <h4 class="modal-title">删除活动页</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- 模态框主体 -->
      <div class="delzjybody modal-body">确定要删除吗？</div>
      <!-- 模态框底部 -->
      <div class="delzjy_footer modal-footer"></div>
    </div>
  </div>
</div>

<!-- 分享模态框 -->
<div class="modal fade" id="Zjy-Share">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <!-- 模态框头部 -->
      <div class="modal-header">
        <h4 class="modal-title">分享活动</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- 模态框主体 -->
      <div class="modal-body"></div>
      <!-- 模态框底部 -->
      <div class="modal-footer">
        <span class="CopyDwzBtn"></span>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">

// 隐藏全局信息提示框
function closesctips(){
  $("#Add-Active-ProJect .Result_Tips").css('display','none');
  $("#Creat-User .Result_Tips").css('display','none');
  $("#Result_Tips").css('display','none');
}

// 添加域名
function Addym(){
    $.ajax({
      type: "POST",
      url: "Addyuming-do.php",
      data: $('#Addym').serialize(),
      success: function (data) {
        if (data.result == 100) {
          $("#Result_Tips").css("display","block");
          $("#Result_Tips").html("<div class=\"alert alert-success\">"+data.msg+"</div>");
          location.reload();
        }else{
          $("#Result_Tips").css("display","block");
          $("#Result_Tips").html("<div class=\"alert alert-danger\">"+data.msg+"</div>");
        }
      },
      error: function() {
        $("#Result_Tips").css("display","block");
        $("#Result_Tips").html("<div class=\"alert alert-danger\">服务器发生错误，请F12检查网络请求。</div>");
      }
    });
    setTimeout('closesctips()', 2000);
}

// 删除域名
function Delym(event){
    var ymid = event.id;
    if (window.confirm("确定要删除吗？")) {
        $.ajax({
          type: "GET",
          url: "Delyuming-do.php?ymid="+ymid,
          success: function (data) {
          	if (data.result == 100) {
            	location.reload();
          	}else{
          		alert('删除失败');
          	}
          },
          error: function() {
            alert('服务器发生错误，请F12检查网络请求。');
          }
        });
    }
}

// 创建用户
function Creatuser(){
    $.ajax({
      type: "POST",
      url: "Add-User-do.php",
      data: $('#Creatuser').serialize(),
      success: function (data) {
        if (data.code == 100) {
          $("#Creat-User .Result_Tips").css("display","block");
          $("#Creat-User .Result_Tips").html("<div class=\"alert alert-success\">"+data.msg+"</div>");
          location.reload();
        }else{
          $("#Creat-User .Result_Tips").css("display","block");
          $("#Creat-User .Result_Tips").html("<div class=\"alert alert-danger\">"+data.msg+"</div>");
        }
      },
      error: function() {
        $("#Creat-User .Result_Tips").css("display","block");
        $("#Creat-User .Result_Tips").html("<div class=\"alert alert-danger\">服务器发生错误，请F12检查网络请求。</div>");
      }
    });
    setTimeout('closesctips()', 2000);
}

// 删除用户
function Deluser(event){
    var userid = event.id;
    if (window.confirm("同时删除该用户创建的所有数据，确定要删除吗？")) {
        $.ajax({
          type: "GET",
          url: "Del-User-do.php?userid="+userid,
          success: function (data) {
          	if (data.code == 100) {
            	location.reload();
          	}else{
          		alert(data.msg);
          	}
          },
          error: function() {
            alert('服务器发生错误，请F12检查网络请求。');
          }
        });
    }
}

</script>
</body>
</html>