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
    <h2 class="big-title">淘宝客工具箱 - 配置</h2>
    <p class="tips">工具箱的一些参数、设置、以及个性化修改等说明。</p>
    <ul class="nav nav-pills" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="pill" href="#home">配置项</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="javascript:;" data-toggle="modal" data-target="#Add-ym" style="outline:none;">添加域名</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="javascript:;" data-toggle="modal" data-target="#Creat-User" style="outline:none;">添加账号</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./DwzApi.php" style="outline:none;">短网址API</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./ShouQuan.php" style="outline:none;">授权说明</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php">返回首页</a>
      </li>
    </ul><br/>';
    
    // 落地域名配置
    if ($result_yuming->num_rows > 0) {
      echo '<table class="table" style="margin-top:15px;background:#f1f1f1;">';
      echo '<h4>落地域名</h4>';
      echo '<thead><tr><th class="dbt">域名</th><th style="font-size:15px;">类型</th><th style="font-size:15px;" class="caozuo">操作</th></tr></thead>';
      while($row_yuming = $result_yuming->fetch_assoc()) {
        
        $id = $row_yuming["id"];
        $yuming = $row_yuming["yuming"];
        $type = $row_yuming["type"];
        
        echo '
        <tbody>
        <tr>
        <td>'.$yuming.'</td>
        <td style="font-size:15px;">'.$type.'</td>
        <td class="caozuo">
        <a href="javascript:;" class="card-link" id="'.$id.'" onclick="Delym(this);" style="margin-left:0;">删除</a>
        </td>
        </tr>
        </tbody>';
      }
      echo '</table>';
    }else{
      echo '<table class="table" style="margin-top:15px;">';
      echo "<p style='color:#999;font-size:15px;text-align:center;border-top:1px solid #eee;'><img src='../images/nodata.png' style='display:block;width:100px;margin:30px auto 0;opacity:0.3;' />暂无域名</p>";
      echo '</table>';
    }
    
    // 用户配置
    if ($result_userlist->num_rows > 0) {
      echo '<table class="table" style="margin-top:15px;background:#f1f1f1;">';
      echo '<h4>用户账号</h4>';
      echo '<thead><tr><th class="dbt" style="width:150px;">账号</th><th style="font-size:15px;">PID</th><th style="font-size:15px;" class="caozuo">操作</th></tr></thead>';
      while($row_userlist = $result_userlist->fetch_assoc()) {
          
        $user_id = $row_userlist["user_id"];
        $user_name = $row_userlist["user_name"];
        $pid = $row_userlist["pid"];
        
        echo '
        <tbody>
        <tr>
        <td style="width:150px;">'.$user_name.'</td>
        <td style="font-size:15px;">'.$pid.'</td>
        <td class="caozuo">
        <a href="javascript:;" class="card-link" id="'.$user_id.'" onclick="Deluser(this);" style="margin-left:0;">删除</a>
        </td>
        </tr>
        </tbody>';
      }
      echo '</table>';
    }
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
            <input type="text" name="appkey" class="form-control" placeholder="折淘客的对接秘钥appkey">
          </div>
          <div class="form-group">
            <input type="text" name="user_email" class="form-control" placeholder="输入邮箱">
          </div>
          <div class="form-group">
            <!--<input type="text" name="tbname" class="form-control" placeholder="输入淘宝账号">-->
            <input type="text" name="tbname" class="form-control" placeholder="折淘客的淘客账号授权ID（SID）">
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