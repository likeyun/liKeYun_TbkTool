<!-- 交流群：http://www.liketube.cn/ma/common/qun/redirect/?hmid=19122 -->
<!-- 交流群：http://www.liketube.cn/ma/common/qun/redirect/?hmid=19122 -->
<!-- 交流群：http://www.liketube.cn/ma/common/qun/redirect/?hmid=19122 -->
<!-- 交流群：http://www.liketube.cn/ma/common/qun/redirect/?hmid=19122 -->
<!-- 交流群：http://www.liketube.cn/ma/common/qun/redirect/?hmid=19122 -->
<!-- 交流群：http://www.liketube.cn/ma/common/qun/redirect/?hmid=19122 -->
<!-- 交流群：http://www.liketube.cn/ma/common/qun/redirect/?hmid=19122 -->
<!-- 交流群：http://www.liketube.cn/ma/common/qun/redirect/?hmid=19122 -->
<!-- 交流群：http://www.liketube.cn/ma/common/qun/redirect/?hmid=19122 -->
<!-- 交流群：http://www.liketube.cn/ma/common/qun/redirect/?hmid=19122 -->
<!-- 交流群：http://www.liketube.cn/ma/common/qun/redirect/?hmid=19122 -->
<!-- 交流群：http://www.liketube.cn/ma/common/qun/redirect/?hmid=19122 -->
<!-- 交流群：http://www.liketube.cn/ma/common/qun/redirect/?hmid=19122 -->
<!-- 交流群：http://www.liketube.cn/ma/common/qun/redirect/?hmid=19122 -->
<!DOCTYPE html>
<html>
<head>
  <title>淘宝客工具箱 - 淘口令解析/淘口令生成/淘宝客中间页/淘宝联盟API/淘宝转链</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://cdn.staticfile.org/jquery/3.2.1/jquery.min.js"></script>
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

  //计算中间页总量
  $sql_zjy_num = "SELECT * FROM tbk_zjy";
  $result_zjy_num = $conn->query($sql_zjy_num);
  $allzjy_num = $result_zjy_num->num_rows;

  //每页显示的活码数量
  $lenght = 10;

  //当前页码
  @$page = $_GET['page']?$_GET['page']:1;

  //每页第一行
  $offset = ($page-1)*$lenght;

  //总数页
  $allpage = ceil($allzjy_num/$lenght);

  //上一页     
  $prepage = $page-1;

  //下一页
  $nextpage = $page+1;

  // 获得中间页列表
  $sql_zjy = "SELECT * FROM tbk_zjy ORDER BY ID DESC limit {$offset},{$lenght}";
  $result_zjy = $conn->query($sql_zjy);

  echo '<div class="container">
    <h2 class="big-title">淘宝客工具箱（v2.0.2）</h2>
    <p class="tips">本面板提供淘口令中间页的编辑、删除、生成海报、复制链接、查看数据等功能，也是系统的首页。</p>
    <ul class="nav nav-pills" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="pill" href="#home" id="quanju_btn_bgcolor">中间页</a>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link" href="Creat-Zjy.php">手动创建</a>
      </li> -->
      <li class="nav-item">
        <a class="nav-link" href="AutoCreat-Zjy.php">快速创建</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="Creat-ActiveZjy.php">活动页</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="Pid-Set.php">系统设置</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="Exit-Login.php">退出登录</a>
      </li>
    </ul><br/>';

    if ($result_zjy->num_rows > 0) {
      echo '<table class="table"><thead><tr><th style="overflow:hidden;width:150px;font-size:15px;">标题</th><th style="font-size:15px;">访问量</th><th style="font-size:15px;">操作</th></tr></thead>';
      while($row_zjy = $result_zjy->fetch_assoc()) {
        $zid = $row_zjy["zjy_id"];
        $zjy_long_title = $row_zjy["zjy_long_title"];
        $zjy_short_title = $row_zjy["zjy_short_title"];
        $zjy_yprice = $row_zjy["zjy_yprice"];
        $zjy_qhprice = $row_zjy["zjy_qhprice"];
        $zjy_cover = $row_zjy["zjy_cover"];
        $zjy_template = $row_zjy["zjy_template"];
        $zjy_dwz = $row_zjy["zjy_dwz"];
        $zjy_pv = $row_zjy["zjy_pv"];
        $zjy_tkl = $row_zjy["zjy_tkl"];

        echo '
			    <tbody>
			      <tr>
			        <td style="overflow:hidden;width:150px;font-size:15px;">'.$zjy_short_title.'</td>
			        <td style="font-size:15px;">'.$zjy_pv.'</td>
			        <td><a href="Edi-Zjy.php?zid='.$zid.'" class="card-link" style="font-size:15px;">编辑</a> <a href="javascript:;" id="'.$zid.'" onclick="ShareZjy(this);" data-toggle="modal" data-target="#Zjy-Share" style="font-size:15px;">分享</a> <a href="#" class="card-link" id="'.$zid.'" onclick="GetZjyId(this);" data-toggle="modal" data-target="#delzjy_model" style="font-size:15px;">删除</a></td>
			      </tr>
			    </tbody>';
      }
      echo '</table>';
      // 分页判断
      if ($allzjy_num <= 10) {
        // 所有记录的总数<当前页面显示总数
      }else if ($page == 1 && $allzjy_num > 10) {
        // 当前页面是第一页并且所有记录的总数大于当前页显示的总数
        echo '<ul class="pagination">
        <li class="page-item"><a class="page-link" href="?page=">第'.$page.'页，共'.$allpage.'页</a></li>
        <li class="page-item"><a class="page-link" href="?page='.$nextpage.'">下一页</a></li>
        <li class="page-item"><a class="page-link" href="?page='.$allpage.'">尾页</a></li>
        </ul>
        </div>';
      }else if ($page == $allpage) {
        // 当前页面已经是最后一页的时候
        echo '<ul class="pagination">
        <li class="page-item"><a class="page-link" href="?page=1">首页</a></li>
        <li class="page-item"><a class="page-link" href="?page='.$prepage.'">上一页</a></li>
        <li class="page-item"><a class="page-link" href="?page=">第'.$page.'页，已经是最后一页</a></li>
        </ul>
        </div>';
      }else{
        // 既不是第一页、也不是最后一页
        echo '<ul class="pagination">
        <li class="page-item"><a class="page-link" href="?page=1">首页</a></li>
        <li class="page-item"><a class="page-link" href="?page='.$prepage.'">上一页</a></li>
        <li class="page-item"><a class="page-link" href="?page=">第'.$page.'页，共'.$allpage.'页</a></li>
        <li class="page-item"><a class="page-link" href="?page='.$nextpage.'">下一页</a></li>
        <li class="page-item"><a class="page-link" href="?page='.$allpage.'">尾页</a></li>
        </ul>
        </div>';
      }
      echo "<br/><br/>";
    }else{
      echo "暂无中间页，<a href='AutoCreat-Zjy.php'>点击创建</a>";
    }
}else{
  echo '<script>location.href="../admin/Login.php";</script>';
}
?>

<!-- 删除中间页模态框 -->
<div class="modal fade" id="delzjy_model">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <!-- 模态框头部 -->
      <div class="modal-header">
        <h4 class="modal-title">删除中间页</h4>
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
        <h4 class="modal-title">分享链接</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- 模态框主体 -->
      <div class="modal-body"></div>
      <!-- 模态框底部 -->
      <div class="modal-footer">
        <span class="CopyDwzBtn" style="margin:0 auto;"></span>
      </div>
    </div>
  </div>
</div>

<!-- Ajax -->
<script type="text/javascript">
// 获取中间页的zid
function GetZjyId(event){
  var Zjyid = event.id;
    $("#delzjy_model .modal-dialog .modal-footer").html("<button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\" id="+Zjyid+" onclick=\"DelZjy(this);\">确定删除</button>");
}

// 删除中间页
function DelZjy(event){
  var DelZjyId = event.id;
  $.ajax({
      type: "GET",
      url: "Del-Zjy.php?zid="+DelZjyId,
      success: function (data) {
        if (data.result == "100") {
          location.reload();
        }else{
          alert("未知错误");
        }
      },
      error : function() {
        alert("服务器发生错误");
      }
  });
}

// 分享中间页
function ShareZjy(event){
  var ZjyId = event.id;
  $.ajax({
      type: "GET",
      url: "Share-Zjy.php?zid="+ZjyId,
      success: function (data) {
        $("#Zjy-Share .modal-body").html("<p style='word-wrap:break-word;'>长链接："+data.longUrl+"<p><p>短链接："+data.dwz+"<p></p><div style='width:200px;height:200px;background:#eee;margin:10px auto;'><img src='../api/qrcode.php?content="+data.longUrl+"' width='200'/></div>");
        $("#Zjy-Share .modal-footer .CopyDwzBtn").html('<button type="button" class="btn btn-secondary" data-clipboard-text='+data.dwz+' id="quanju_btn_bgcolor">复制短链接</button>');
      },
      error : function() {
        alert("服务器发生错误");
      }
  });
}

// 复制链接
var clipboard = new Clipboard('#Zjy-Share .modal-footer .CopyDwzBtn .btn-secondary');
clipboard.on('success', function (e) {
    $("#Zjy-Share .modal-footer .CopyDwzBtn").html('<button type="button" class="btn btn-secondary" id="quanju_btn_bgcolor">已复制</button>');
});
clipboard.on('error', function(e) {
    document.querySelector('#Zjy-Share .modal-footer .CopyDwzBtn .btn-secondary');
    alert("复制失败");
});
</script>
</body>
</html>