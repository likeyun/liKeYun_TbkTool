<!DOCTYPE html>
<html>
<head>
  <title>淘宝客工具箱 - 公众号配置</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://cdn.staticfile.org/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdn.staticfile.org/popper.js/1.15.0/umd/popper.min.js"></script>
  <script src="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../css/style.css">
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

  // 引入数据库配置
  include '../Db_Connect.php';

  // 连接数据库
  $conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
  mysqli_query($conn, "SET NAMES UTF-8");

  // 获得落地页域名列表
  $sql_gzhset = "SELECT * FROM tbk_gzh_set";
  $result_gzhset = $conn->query($sql_gzhset);

  echo '<div class="container">
    <h2 class="big-title">淘宝客工具箱 - 公众号配置</h2>
    <p class="tips">本平台支持接入公众号，通过公众号快速创建中间页，<a href="https://mp.weixin.qq.com/s?__biz=MzU2NzIyMzA1Mw==&mid=100000259&idx=1&sn=cc2d70d665722244521558113249f3b7&chksm=7ca134614bd6bd772e2ecd759a3562c0a363bfe698828cdce3d58a826485d35c6397544a1ae4#rd">详情戳这里查看>></a> </p>
    <ul class="nav nav-pills" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="pill" href="#home">公众号配置</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php">返回首页</a>
      </li>
    </ul><br/>';

    if ($result_gzhset->num_rows > 0) {
      while($row_gzhset = $result_gzhset->fetch_assoc()) {
        // 配置列表
        $gzh_set_obj = $row_gzhset["gzh_set_obj"]; // 设置项目
        $gzh_set_val = $row_gzhset["gzh_set_val"]; // 设置值
        
        echo '<div class="card">
        <div class="card-body">
        <h4 class="card-title">'.$gzh_set_obj.'：'.$gzh_set_val.'</h4>
        <a href="javascript:;" class="card-link" id="'.$gzh_set_obj.'" onclick="GetSet(this);" data-toggle="modal" data-target="#gzhset_model">编辑</a>
        </div>
        </div>
        <br/>';
      }

      // 获取公众号服务器配置地址
      $sql_gzhset_token = "SELECT * FROM tbk_gzh_set WHERE gzh_set_obj='Token'";
      $result_gzhset_token = $conn->query($sql_gzhset_token);
      if ($result_gzhset_token->num_rows > 0) {
        while($row_gzhset_token = $result_gzhset_token->fetch_assoc()) {
          $token = $row_gzhset_token["gzh_set_val"];
        }
        $gzhurl = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/gzh.php?t=".$token;
      }else{
        // 
      }

      // 公众号URL
      echo "<h3>公众号服务器地址</h3>";
      echo '<div class="card" style="margin-top:10px;">
        <div class="card-body">'.$gzhurl.'</div>
      </div>';
      echo "<br/><h3>Token</h3>";
      echo '<div class="card" style="margin-top:10px;">
        <div class="card-body">'.$token.'</div>
      </div>';
      echo '<div class="card" style="margin-top:10px;">
        <div class="card-body"><b>配置说明：</b>登陆公众号，点击基本配置，在服务器配置中修改配置，URL就是上面的公众号服务器地址，Token就是上面你设置的Token，EncodingAESKey随机生成即可，然后点击提交，提交后点击启用。要提前告诉你的是，一旦启用，你公众号原先设置的自动回复和自定义菜单就会失效。<br/><br/><b>使用说明：</b>配置完成之后，你就可以在公众号使用快速创建中间页的功能了，第一次使用，需要在公众号注册你的使用权限，向公众号发送 <span class="badge badge-dark">注册+注册验证码</span> 即可注册成功，例如你的注册验证码是123456，那么你需要向公众号发送 <span class="badge badge-dark">注册123456</span> 即可注册成功。注册成功后，直接到淘宝联盟APP复制你要创建中间页的淘口令文案即可快速创建！<a href="https://share.weiyun.com/VXVZP58C">视频教程>></a> </div>
      </div><br/><br/><br/><br/>';

    }else{
      echo "暂无配置";
    }
    echo '</div>';
}else{
  echo header('Location:../admin/Login.php');
}
?>

<!-- 配置公众号模态框 -->
<div class="modal fade" id="gzhset_model">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <!-- 模态框头部 -->
      <div class="modal-header">
        <h4 class="modal-title">请输入配置</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- 模态框主体 -->
      <div class="gzhset-body modal-body">
        <form role="form" action="##" onsubmit="return false" method="post" id="SetGzh">
        <div class="form-group"></div>
        <button type="submit" class="btn btn-primary" style="background: #333;border:none;" onclick="PostSet();">确定配置</button>
      </form>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
// 获取设置项
function GetSet(event){
  var SetObj = event.id;
    $("#gzhset_model .modal-dialog .modal-body .form-group").html("<input type=\"text\" name=\"setval\" class=\"form-control\" placeholder=\"请设置"+SetObj+"\"><input type=\"hidden\" name=\"setobj\" value=\""+SetObj+"\" class=\"form-control\">");
}

// 提交设置
function PostSet(){
  $.ajax({
      type: "POST",
      url: "Gzh-Set.php",
      data: $('#SetGzh').serialize(),
      success: function (data) {
        if (data.result == 100) {
          location.reload();
        }else{
          alert(data.msg)
        }
      },
      error : function() {
        alert("服务器发生错误");
      }
  });
}
</script>
</body>
</html>