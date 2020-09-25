<!DOCTYPE html>
<html>
<head>
  <title>淘宝客工具箱 - 登陆</title>
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
<body style="background: #eee;">
<form role="form" action="##" onsubmit="return false" method="post" id="login">
  <div id="login-con">
    <div class="left">
      <img src="../images/tblm-icon.png">
    </div>
    <div class="right">
      <div class="form">
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text">账号</span>
          </div>
          <input type="text" name="user" class="form-control" placeholder="请输入账号">
        </div>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text">密码</span>
          </div>
          <input type="password" name="pwd" class="form-control" placeholder="请输入密码">
        </div>
        <button type="button" class="btn btn-dark" style="width: 100%;" onclick="login();">立即登录</button>
        <div class="Result"></div>
      </div>
    </div>
  </div>
</form>
<!-- 登录 -->
<script type="text/javascript">
  function closesctips(){
    $("#login-con .form .Result").css('display','none');
  }

  function login(){
      $.ajax({
          type: "POST",
          url: "Login-Check.php",
          data: $('#login').serialize(),
          success: function (data) {
            // 登录成功
            if (data.result == "100") {
               $("#login-con .form .Result").css('display','block');
               $("#login-con .form .Result").html('<div class="alert alert-success"><strong>'+data.msg+'</strong>正在跳转至首页</div>');
               location.href='index.php';
            }else{
               $("#login-con .form .Result").css('display','block');
               $("#login-con .form .Result").html('<div class="alert alert-danger"><strong>'+data.msg+'</strong></div>');
            }
            // 关闭提示
            setTimeout('closesctips()', 2000);
          },
          error : function() {
            // 更新失败
             $(".container .Result").css('display','block');
             $(".container .Result").html('<div class="alert alert-danger"><strong>服务器错误，请检查配置文件</strong></div>');
             setTimeout('closesctips()', 2000);
          }
      });
  }
</script>
</body>
</html>