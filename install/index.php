<!DOCTYPE html>
<html>
<head>
  <title>活码系统安装环境检测</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://cdn.staticfile.org/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdn.staticfile.org/popper.js/1.15.0/umd/popper.min.js"></script>
  <script src="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <link href="https://cdn.bootcdn.net/ajax/libs/open-iconic/1.0.0/font/css/open-iconic.min.css" rel="stylesheet">
  <link href="https://cdn.bootcdn.net/ajax/libs/open-iconic/1.0.0/font/css/open-iconic-bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php
  // 检测php环境版本
  $phpv = PHP_VERSION;

  // 检测是否可以创建文件
  file_put_contents("../test.txt","test 777");
?>

<div class="container" style="width: 800px;">
  <br/>
  <br/>
  <br/>
  <h2>活码系统安装环境检测</h2>
  <p>你的服务器需要具备以下要求才可以安装本程序</p>            
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>环境名称</th>
        <th>当前状态</th>
        <th>是否符合</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>php版本</td>
        <td>当前：<?php echo $phpv; ?>，要求php5.6~7.0 </td>
        <td>
          <?php
          if($phpv > '5.6' && $phpv < '7.1'){
            echo '<span class="oi oi-circle-check" style="color: #07C160;"></span>';
          }else{
            echo '<span class="oi oi-circle-x" style="color: #FA5151;"></span>';
          }
          ?>
        </td>
      </tr>
      <tr>
        <td>创建文件权限</td>
        <td>
          <?php
            if (file_exists("../test.txt")) {
              echo "已开启";
            }else{
              echo "未开启";
            }
          ?>
        </td>
        <td>
          <?php
          if (file_exists("../test.txt")) {
            echo '<span class="oi oi-circle-check" style="color: #07C160;"></span>';
          }else{
            echo '<span class="oi oi-circle-x" style="color: #FA5151;"></span>';
          }
          ?>
        </td>
      </tr>
    </tbody>
</table>
<!-- 前往安装按钮 -->

<?php
  if ($phpv > '5.6' && $phpv < '7.1' && file_exists("../test.txt")) {
    echo '<button type="button" onclick="install();" class="btn btn-success">开始安装</button>';
    // 删除测试文件
    unlink("../test.txt");
  }
?>
</div>

<script>
function install() {
  location.href="form.html";
}
</script>
</body>
</html>