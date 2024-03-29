<!DOCTYPE html>
<html>
<head>
  <title>淘宝客工具箱 - 淘口令解析/淘口令生成/淘宝客中间页/淘宝联盟API/淘宝转链</title>
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

  // 计算中间页总量
  $sql_zjy_num = "SELECT * FROM tbk_zjy WHERE user = '$user'";
  $result_zjy_num = $conn->query($sql_zjy_num);
  $allzjy_num = $result_zjy_num->num_rows;

  // 每页显示的活码数量
  $lenght = 10;

  // 当前页码
  @$page = $_GET['page']?$_GET['page']:1;

  // 每页第一行
  $offset = ($page-1)*$lenght;

  // 总数页
  $allpage = ceil($allzjy_num/$lenght);

  // 上一页     
  $prepage = $page-1;

  // 下一页
  $nextpage = $page+1;

  // 获得中间页列表
  $sql_zjy = "SELECT * FROM tbk_zjy WHERE user = '$user' ORDER BY ID DESC limit {$offset},{$lenght}";
  $result_zjy = $conn->query($sql_zjy);

  echo '<div class="container">
    <h2 class="big-title">淘宝客工具箱（v3.0）</h2>
    <p class="tips">淘口令解析、转换、创建中间页、创建分享。</p>
    <ul class="nav nav-pills" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="pill" href="#home">中间页</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="Creat-Zjy.php?token='.md5('创建链接'.time()).'&lang=zh_CN&charset=utf-8">创建链接</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="Creat-ActiveZjy.php?token='.md5('活动页'.time()).'&lang=zh_CN&charset=utf-8">活动页</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="Peizhi.php?token='.md5('工具箱配置'.time()).'&lang=zh_CN&charset=utf-8">工具箱配置</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="Exit-Login.php?token='.md5('工具箱配置'.time()).'&lang=zh_CN&charset=utf-8">退出登录</a>
      </li>
    </ul><br/>';

    if ($result_zjy->num_rows > 0) {
      echo '<table class="table"><thead><tr><th class="dbt">标题</th><th style="font-size:15px;">访问量</th><th style="font-size:15px;" class="caozuo">操作</th></tr></thead>';
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

        echo '<tbody>
        <tr>
        <td>'.$zjy_short_title.'</td>
        <td style="font-size:15px;">'.$zjy_pv.'</td>
        <td class="caozuo"><a href="Edi-Zjy.php?zid='.$zid.'&token='.md5($zid).'" class="card-link" style="font-size:15px;">编辑</a> <a href="javascript:;" id="'.$zid.'" onclick="ShareZjy(this);" data-toggle="modal" data-target="#Zjy-Share" style="font-size:15px;">分享</a> <a href="#" class="card-link" id="'.$zid.'" onclick="GetZjyId(this);" data-toggle="modal" data-target="#delzjy_model" style="font-size:15px;">删除</a></td>
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
        <li class="page-item"><a class="page-link" href="?page='.$nextpage.'">下一页</a></li>
        <li class="page-item"><a class="page-link" href="?page='.$allpage.'">尾页</a></li>
        <li class="page-item"><a class="page-link" href="?page=">第'.$page.'页，共'.$allpage.'页</a></li>
        </ul>
        </div>';
      }else if ($page == $allpage) {
        // 当前页面已经是最后一页的时候
        echo '<ul class="pagination">
        <li class="page-item"><a class="page-link" href="?page=1">首页</a></li>
        <li class="page-item"><a class="page-link" href="?page='.$prepage.'">上一页</a></li>
        <li class="page-item"><a class="page-link" href="?page=">第'.$page.'页，共'.$allpage.'页</a></li>
        </ul>
        </div>';
      }else{
        // 既不是第一页、也不是最后一页
        echo '<ul class="pagination">
        <li class="page-item"><a class="page-link" href="?page=1">首页</a></li>
        <li class="page-item"><a class="page-link" href="?page='.$prepage.'">上一页</a></li>
        <li class="page-item"><a class="page-link" href="?page='.$nextpage.'">下一页</a></li>
        <li class="page-item"><a class="page-link" href="?page='.$allpage.'">尾页</a></li>
        <li class="page-item"><a class="page-link" href="?page=">第'.$page.'页，共'.$allpage.'页</a></li>
        </ul>
        </div>';
      }
      echo "<br/><br/>";
    }else{
      echo "<p style='color:#999;font-size:15px;text-align:center;border-top:1px solid #eee;'><img src='../images/nodata.png' style='display:block;width:100px;margin:30px auto 0;opacity:0.3;' />暂无中间页</p>";
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
        <span class="CopyDwzBtn"></span>
      </div>
    </div>
  </div>
</div>

<!-- Ajax -->
<script type="text/javascript">
// 获取中间页的zid
function GetZjyId(event){
  var Zjyid = event.id;
    $("#delzjy_model .modal-dialog .modal-footer").html("<button type=\"button\" class=\"btn-zdy\" data-dismiss=\"modal\" id="+Zjyid+" onclick=\"DelZjy(this);\">确定删除</button>");
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
        $("#Zjy-Share .modal-footer .CopyDwzBtn").html('<button type="button" class="btn btn-secondary" data-clipboard-text='+data.dwz+'>复制链接</button>');
      },
      error : function() {
        alert("服务器发生错误");
      }
  });
}

// 复制链接
var clipboard = new Clipboard('#Zjy-Share .modal-footer .CopyDwzBtn .btn-secondary');
clipboard.on('success', function (e) {
    $("#Zjy-Share .modal-footer .CopyDwzBtn").html('<button type="button" class="btn btn-secondary">已复制</button>');
});
clipboard.on('error', function(e) {
    document.querySelector('#Zjy-Share .modal-footer .CopyDwzBtn .btn-secondary');
    alert("复制失败");
});
</script>
</body>
</html>