<!DOCTYPE html>
<html>
<head>
  <title>活码系统安装环境检测</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <script src="../js/jquery.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <link href="https://cdn.bootcdn.net/ajax/libs/open-iconic/1.0.0/font/css/open-iconic.min.css" rel="stylesheet">
  <link href="https://cdn.bootcdn.net/ajax/libs/open-iconic/1.0.0/font/css/open-iconic-bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php
  // 检测php环境版本
  $phpv = PHP_VERSION;

  // 检测是否可以创建文件
  file_put_contents("../test.txt","777");
?>

<div class="container" style="width: 800px;">
  <br/>
  <br/>
  <br/>
  <h2 style="text-align:center;">淘宝客工具箱安装环境检测</h2>
  <p style="text-align:center;color:#999;">你的服务器需要具备以下要求才可以安装本程序</p>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>环境</th>
        <th>当前</th>
        <th>状态</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>php版本</td>
        <td>当前：<?php echo $phpv; ?>，要求php5.6~7.4 </td>
        <td>
          <?php
          if($phpv >= '5.6' && $phpv <= '7.5'){
            echo '<span class="oi oi-circle-check" style="color: #07C160;"></span>';
          }else{
            echo '<span class="oi oi-circle-x" style="color: #FA5151;"></span>';
          }
          ?>
        </td>
      </tr>
      <tr>
        <td>写入权限</td>
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
  if ($phpv >= '5.6' && $phpv <= '7.5' && file_exists("../test.txt")) {
    echo '<button type="button" onclick="install();" class="btn btn-success" style="display:block;margin:10px auto;background:#FF5000;border:none;">开始安装</button>';
    // 删除测试文件
    unlink("../test.txt");
  }
?>
</div>

<script>
function install() {
  location.href="install.html";
}
</script>
</body>
</html>
