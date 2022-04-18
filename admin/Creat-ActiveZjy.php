<!DOCTYPE html>
<html>
<head>
  <title>淘宝客工具箱 - 活动中间页</title>
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
  $sql_zjy_num = "SELECT * FROM tbk_active_zjy WHERE user = '$user'";
  $result_zjy_num = $conn->query($sql_zjy_num);
  $allzjy_num = $result_zjy_num->num_rows;

  // 每页显示的活码数量
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

  // 获得落地页域名列表
  $sql_active_yuming = "SELECT * FROM tbk_yuming";
  $result_active_yuming = $conn->query($sql_active_yuming);

  // 获得中间页列表
  $sql_zjy = "SELECT * FROM tbk_active_zjy WHERE user = '$user' ORDER BY ID DESC limit {$offset},{$lenght}";
  $result_zjy = $conn->query($sql_zjy);

  echo '<div class="container">
    <h2 class="big-title">淘宝客工具箱 - 活动页</h2>
    <p class="tips">创建一个凑单、拼单活动、或者是需要多个步骤参与的活动的中间页。</p>
    <ul class="nav nav-pills" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="pill" href="#home">活动页</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="javascript:;" data-toggle="modal" data-target="#Active-Zjy" style="outline:none;">创建活动</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php">返回首页</a>
      </li>
    </ul><br/>';

    if ($result_zjy->num_rows > 0) {
      echo '<table class="table"><thead><tr><th class="dbt">标题</th><th style="font-size:15px;">访问量</th><th style="font-size:15px;" class="caozuo">操作</th></tr></thead>';
      while($row_zjy = $result_zjy->fetch_assoc()) {
          
        // 获得活动中间页的数据
        $active_id = $row_zjy["active_id"];
        $active_title = $row_zjy["active_title"];
        $active_pv = $row_zjy["active_pv"];
        
        echo '<tbody>
        <tr>
        <td>'.$active_title.'</td>
        <td style="font-size:15px;">'.$active_pv.'</td>
        <td class="caozuo">
        <a href="javascript:;" class="card-link" data-toggle="modal" data-target="#Edi-ActiveZjy" style="outline:none;" id="'.$active_id.'" onclick="edi_active_id(this);" style="margin-left:0;">编辑</a>
        <a href="#" class="card-link" id="'.$active_id.'" onclick="ShareZjy(this);" data-toggle="modal" data-target="#Zjy-Share" style="margin-left:0;">分享</a>
        <a href="#" class="card-link" id="'.$active_id.'" onclick="Get_ActiveZjy_Id(this);" data-toggle="modal" data-target="#delzjy_model" style="margin-left:0;">删除</a>
        </td>
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
        <li class="page-item"><a class="page-link" href="Creat-ActiveZjy.php?page='.$nextpage.'">下一页</a></li>
        <li class="page-item"><a class="page-link" href="Creat-ActiveZjy.php?page='.$allpage.'">尾页</a></li>
        <li class="page-item"><a class="page-link" href="Creat-ActiveZjy.php?page=">第'.$page.'页，共'.$allpage.'页</a></li>
        </ul>
        </div>';
      }else if ($page == $allpage) {
        // 当前页面已经是最后一页的时候
        echo '<ul class="pagination">
        <li class="page-item"><a class="page-link" href="Creat-ActiveZjy.php?page=1">首页</a></li>
        <li class="page-item"><a class="page-link" href="Creat-ActiveZjy.php?page='.$prepage.'">上一页</a></li>
        <li class="page-item"><a class="page-link" href="Creat-ActiveZjy.php?page=">第'.$page.'页，共'.$allpage.'页</a></li>
        </ul>
        </div>';
      }else{
        // 既不是第一页、也不是最后一页
        echo '<ul class="pagination">
        <li class="page-item"><a class="page-link" href="Creat-ActiveZjy.php?page=1">首页</a></li>
        <li class="page-item"><a class="page-link" href="Creat-ActiveZjy.php?page='.$prepage.'">上一页</a></li>
        <li class="page-item"><a class="page-link" href="Creat-ActiveZjy.php?page='.$nextpage.'">下一页</a></li>
        <li class="page-item"><a class="page-link" href="Creat-ActiveZjy.php?page='.$allpage.'">尾页</a></li>
        <li class="page-item"><a class="page-link" href="Creat-ActiveZjy.php?page=">第'.$page.'页，共'.$allpage.'页</a></li>
        </ul>
        </div>';
      }
      echo "<br/><br/>";
    }else{
      echo "<p style='color:#999;font-size:15px;text-align:center;border-top:1px solid #eee;'><img src='../images/nodata.png' style='display:block;width:100px;margin:30px auto 0;opacity:0.3;' />暂无中间页</p>";
    }
}else{
  echo header('Location:../admin/Login.php');
}
?>

<!-- 创建活动页 -->
<div class="modal fade" id="Active-Zjy">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <!-- 模态框头部 -->
      <div class="modal-header">
        <h4 class="modal-title">输入活动标题</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- 模态框主体 -->
      <div class="modal-body">
        <form role="form" action="##" onsubmit="return false" method="post" id="Creat_Active">
          <div class="form-group">
            <input type="text" name="active_title" class="form-control" placeholder="请输入标题">
          </div>
          <?php
            if ($result_active_yuming->num_rows > 0) {
              echo '<select class="form-control" style="margin-bottom:15px;" name="active_yuming">';
                // 将当前管理后台的域名添加到落地域名
                echo '<option value="'.$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'">http://'.$_SERVER['HTTP_HOST'].'</option>';
                // 获取服务器中自己添加的落地域名
                while($row_active_yuming = $result_active_yuming->fetch_assoc()) {
                    echo '<option value="'.$row_active_yuming["yuming"].'">'.$row_active_yuming["yuming"].'</option>';
                }
              echo '</select>';
            }else{
              echo '<select class="form-control" style="margin-bottom:15px;" name="active_yuming">';
              // 将当前管理后台的域名添加到落地域名
              echo '<option value="'.$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'">http://'.$_SERVER['HTTP_HOST'].'</option>';
              echo '</select>';
            }

            // 短网址API列表
            echo '
            <select class="form-control" style="margin-bottom:15px;" name="active_dwzapi">
            <option value="2">本地短网址</option>
            <option value="1">不生成短网址</option>
            </select>';
          ?>
          <button type="submit" class="btn-active" onclick="Creat_Active();">创建活动</button>
          <p style="width:100%;height:30px;display:block;text-align:center;color: #666;font-size: 14px;margin-top:20px;">创建成功后请点击【编辑】</p>
          <div id="Result_Tips"></div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- 编辑活动页 -->
<div class="modal fade" id="Edi-ActiveZjy">
  <div class="modal-dialog">
    <div class="modal-content">
 
      <!-- 模态框头部 -->
      <div class="modal-header">
        <h4 class="modal-title">编辑活动</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
 
      <!-- 模态框主体 -->
      <div class="modal-body">
        <ul class="list-group"></ul>
      </div>
 
      <!-- 模态框底部 -->
      <div class="modal-footer">
        <div class="add-project-btn"></div>
      </div>
 
    </div>
  </div>
</div>

<!-- 添加项目 -->
<div class="modal fade" id="Add-Active-ProJect">
  <div class="modal-dialog">
    <div class="modal-content">
 
      <!-- 模态框头部 -->
      <div class="modal-header">
        <h4 class="modal-title">添加活动项目</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
 
      <!-- 模态框主体 -->
      <div class="modal-body">
        <!-- 图片上传 -->
        <form id="upload_active_pic" enctype="multipart/form-data">
          <button type="button" class="btn-zdy" style="margin:0 auto;">
            <input type="file" id="select_active_pic" style="height:50px;" class="file_btn" name="file" />
            <span id="uptext">上传图片</span>
          </button>
        </form>
        <p style="text-align: center;color: #999;font-size: 14px;">不上传则不显示图片</p>
        <!-- 富文本编辑器 -->
        <div id="bianjiqi"></div>
        
        <!-- 是否开启复制 -->
        <select class="form-control copy_open_status" style="margin-top:15px;">
          <option value="">是否开启一键复制</option>
          <option value="1">开启</option>
          <option value="2">关闭</option>
        </select>
        
        <form role="form" action="##" onsubmit="return false" method="post" id="Add_Active_ProJect">
          <input type="hidden" name="active_text" id="active_text_con">
          <input type="hidden" name="active_pic" id="active_pic_con">
          <input type="hidden" name="active_id" id="active_id_con">
          <input type="hidden" name="project_copy_status" id="project_copy_status_con">
          <button type="button" class="btn-zdy" onclick="GetFunWenBenHTMLContent();Add_Active_ProJect();">立即添加</button>
        </form>
        
        <!-- 信息提示框 -->
        <div class="Result_Tips" style="display:none;margin-top:20px;">666</div>
        
      </div>
    </div>
  </div>
</div>

<!-- 编辑项目 -->
<div class="modal fade" id="Edi-Active-ProJect">
  <div class="modal-dialog">
    <div class="modal-content">
 
      <!-- 模态框头部 -->
      <div class="modal-header">
        <h4 class="modal-title">编辑活动项目</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
 
      <!-- 模态框主体 -->
      <div class="modal-body">
        <!-- 图片上传 -->
        <form id="upload_project_pic" enctype="multipart/form-data">
          <button type="button" class="btn-zdy" style="display:block;margin:0 auto;">
            <input type="file" id="select_project_pic" class="file_btn" name="file"/>
            <span id="uptext" class="upbtn">上传/更换图片</span>
          </button>
        </form>
        <p style="text-align: center;color: #999;font-size: 14px;">不上传则不显示图片</p>
        
        <!-- 富文本编辑器 -->
        <div id="edi_bianjiqi"></div>
        
        <!-- 活动标题 -->
        <div class="input-group mb-3" style="margin-top: 15px;">
	      <div class="input-group-prepend">
	        <span class="input-group-text">活动标题</span>
	      </div>
	      <input type="text" class="form-control active_title_con" placeholder="请编辑活动标题">
	    </div>
      </div>
 
      <!-- 模态框底部 -->
      <div class="modal-footer">
        <form role="form" action="##" onsubmit="return false" method="post" id="Edi_ActiveZjy_Project_Action">
          <input type="hidden" name="project_text" class="project_text_con">
          <input type="hidden" name="project_pic" class="project_pic_con">
          <input type="hidden" name="project_id" class="project_id_con">
          <input type="hidden" name="active_title" class="active_title_con">
          <input type="hidden" name="active_id" class="active_id_con">
          <button type="button" class="btn-zdy" onclick="GetFunWenBenHTMLContent_EdiProject();Edi_ActiveZjy_Project_Action();">更新项目</button>
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

<!-- Ajax -->
<script type="text/javascript" src="../js/iceEditor.js"></script>
<script type="text/javascript">

// 实例化富文本编辑器
var FwbHtmlContent = ice.editor('bianjiqi',function(e){
    // 自定义菜单
    e.menu = [
        'backColor', // 字体背景颜色
        'fontSize', // 字体大小
        'foreColor', //字体颜色
        'bold', // 粗体
        'italic', //斜体
        'justifyLeft', //左对齐
        'justifyCenter', //居中对齐
        'justifyRight', //右对齐
    ];
    e.height='120px'; //高度
    // 关闭最大化按钮
    e.maxWindow = false;
    //创建编辑器
    e.create();
    e.getHTML();
});

// 实例化富文本编辑器
var EdiFwbHtmlContent = ice.editor('edi_bianjiqi',function(e){
    // 自定义菜单
    e.menu = [
        'backColor', // 字体背景颜色
        'fontSize', // 字体大小
        'foreColor', //字体颜色
        'bold', // 粗体
        'italic', //斜体
        'justifyLeft', //左对齐
        'justifyCenter', //居中对齐
        'justifyRight', //右对齐
    ];
    e.height='120px'; //高度
    // 关闭最大化按钮
    e.maxWindow = false;
    //创建编辑器
    e.create();
    e.getHTML();
});

// 隐藏全局信息提示框
function closesctips(){
  $("#Add-Active-ProJect .Result_Tips").css('display','none');
  $("#Result_Tips").css('display','none');
}

// 获取项目列表
function GetProJectList(edi_active_id) {
  $.ajax({
      type: "GET",
      url: "Get-Active-Project.php?activeid="+edi_active_id,
      success: function (data) {
        // 防止append重复加载
        $('#Edi-ActiveZjy .modal-body .list-group').empty();
        // 判断结果
        if (data.result == 101) {
          $("#Result_Tips").css("display","block");
          $("#Result_Tips").html("<div class=\"alert alert-danger\">"+data.msg+"</div>");
        }else if (data.result == 102) {
          $("#Result_Tips").css("display","block");
          $("#Result_Tips").html("<div class=\"alert alert-danger\">"+data.msg+"</div>");
        }else if (data.result == 103) {
          $("#Edi-ActiveZjy .modal-body .list-group").html("<p style='text-align:center;color:#999;font-size:15px;'>暂无项目</p>");
        }else{
          // 循环项目列表
          for (var i in data){
            // 项目id
            var project_id = data[i].active_project_id;
            // 项目内容
            var project_text = data[i].active_text.replace(/<[^>]+>/g,"");

            $("#Edi-ActiveZjy .modal-body .list-group").append("<li class=\"list-group-item\"><span style=\"float: left;width:230px;white-space: nowrap; text-overflow: ellipsis;overflow: hidden;word-break: break-all;font-size:15px;color:#666;\">"+project_text+"</span><span style=\"float: right;\"><i class=\"oi oi-pencil\" style=\"font-size: 14px;cursor:pointer;\" id=\""+project_id+"\" data-toggle=\"modal\" data-target=\"#Edi-Active-ProJect\" onclick=\"Edi_ActiveZjy_Project(this);\"></i> <i class=\"oi oi-trash\" id=\""+project_id+"@"+edi_active_id+"\" onclick=\"Del_ActiveZjy_Project(this);\" style=\"font-size: 14px;margin-left: 6px;cursor:pointer;\"></i></span></li>");
          }
        }
      },
      error : function() {
        alert("服务器发生错误");
      }
  });
}

// 编辑活动页，添加项目
function edi_active_id(event){
  var edi_active_id = event.id;
  // 把当前的edi_active_id绑定到添加项目的按钮
  $("#Edi-ActiveZjy .modal-footer .add-project-btn").html("<button type=\"button\" class=\"btn-zdy\" data-toggle=\"modal\" data-target=\"#Add-Active-ProJect\" id=\""+edi_active_id+"\" onclick=\"GetActiveZjyID(this);\">添加项目</button>");

  // 获取项目列表
  GetProJectList(edi_active_id);
}

// 把当前要添加项目的中间页id传给表单
function GetActiveZjyID(event){
  var Add_Active_ProJect_zjyid = event.id;
  $("#active_id_con").attr("value",Add_Active_ProJect_zjyid);
  $('#Edi-ActiveZjy').modal('hide');
  // 将富文本编辑器的内容清空
  FwbHtmlContent.setValue('');
//   $('#upbtncon').html('<button type="button" class="btn-zdy" style="display:block;margin:0 auto;"><input type="file" id="select_active_pic" class="file_btn" name="file"/><span id="uptext">上传图片</span></button>');
}

// 分享中间页
function ShareZjy(event){
  var ActiveZjyId = event.id;
  $.ajax({
      type: "GET",
      url: "Share-ActiveZjy.php?activeid="+ActiveZjyId,
      success: function (data) {
        $("#Zjy-Share .modal-body").html("<p style='word-wrap:break-word;'>长链接："+data.longUrl+"<p><p>短链接："+data.dwz+"<p></p><div style='width:200px;height:200px;background:#eee;margin:10px auto;'><img src='../api/qrcode.php?content="+data.longUrl+"' width='200'/></div>");
        $("#Zjy-Share .modal-footer .CopyDwzBtn").html('<button type="button" class="btn btn-secondary" data-clipboard-text='+data.dwz+'>复制链接</button>');
      },
      error : function() {
        alert("服务器发生错误");
      }
  });
}

// 创建活动页
function Creat_Active(){
  $.ajax({
      type: "POST",
      url: "Creat-ActiveZjy-do.php",
      data: $('#Creat_Active').serialize(),
      success: function (data) {
        if (data.result == 100) {
          $("#Result_Tips").css("display","block");
          $("#Result_Tips").html("<div class=\"alert alert-success\">创建成功</div>");
          location.reload();
          setTimeout('closesctips()', 2000);
        }else{
          $("#Result_Tips").css("display","block");
          $("#Result_Tips").html("<div class=\"alert alert-danger\">"+data.msg+"</div>");
          setTimeout('closesctips()', 2000);
        }
      },
      error: function() {
        $("#Result_Tips").css("display","block");
        $("#Result_Tips").html("<div class=\"alert alert-danger\">服务器发生错误，请F12检查网络请求。</div>");
        setTimeout('closesctips()', 2000);
      },
      beforeSend: function() {
        $("#Result_Tips").css("display","block");
        $("#Result_Tips").html("<div class=\"alert alert-danger\">正在创建...</div>");
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

<!-- 富文本编辑器 -->
<script>

  // 获取富文本编辑器的HTML格式的内容(添加项目)
  function GetFunWenBenHTMLContent(){
    var active_text_html = FwbHtmlContent.getHTML();
    $("#active_text_con").attr("value",active_text_html);
  }

  // 获取富文本编辑器的HTML格式的内容(编辑项目)
  function GetFunWenBenHTMLContent_EdiProject() {
    var project_text_html = EdiFwbHtmlContent.getHTML();
    $("#Edi-Active-ProJect .modal-footer .project_text_con").attr("value",project_text_html);
  }

  // 上传图片（添加项目）
  var active_pic_lunxun = setInterval("upload_active_pic()",2000);
    function upload_active_pic() {
    var active_pic_filename = $("#select_active_pic").val();
    if (active_pic_filename) {
      clearInterval(active_pic_lunxun);
      var active_pic_form = new FormData(document.getElementById("upload_active_pic"));
      $.ajax({
        url:"upload.php",
        type:"post",
        data:active_pic_form,
        cache: false,
        processData: false,
        contentType: false,
        success:function(data){
          if (data.res == "400") {
            $("#uptext").text('已上传');
            $("#active_pic_con").attr("value",data.path); // 获取图片地址
          }
        },
        error:function(data){
          $("#uptext").text('上传失败');
        },
        beforeSend:function(data){
          $("#uptext").text('正在上传');
        }
      })
    }else{
      // console.log("等待上传");
    }
  }

  // 上传图片（编辑项目）
  var project_pic_lunxun = setInterval("upload_project_pic()",2000);
    function upload_project_pic() {
    var project_pic_filename = $("#select_project_pic").val();
    if (project_pic_filename) {
      clearInterval(project_pic_lunxun);
      var project_pic_form = new FormData(document.getElementById("upload_project_pic"));
      $.ajax({
        url:"upload.php",
        type:"post",
        data:project_pic_form,
        cache: false,
        processData: false,
        contentType: false,
        success:function(data){
          if (data.res == "400") {
            $("#Edi-Active-ProJect .modal-body .upbtn").text('已上传');
            $("#Edi-Active-ProJect .modal-footer .project_pic_con").attr("value",data.path);
          }
        },
        error:function(data){
          $("#uptext").text('上传失败');
        },
        beforeSend:function(data){
          $("#uptext").text('正在上传');
        }
      })
    }else{
      // console.log("等待上传");
    }
  }

  // 添加项目
  function Add_Active_ProJect(){

      // 把一键复制开启状态传到表单
      var copy_status = $("#Add-Active-ProJect .modal-body .copy_open_status").val();
      $("#project_copy_status_con").attr("value",copy_status);

      // 创建项目
      $.ajax({
          type: "POST",
          url: "Add-Active-ProJect.php",
          data: $('#Add_Active_ProJect').serialize(),
          success: function (data) {
            // 创建成功
            if (data.result == 100) {
              $("#Add-Active-ProJect .Result_Tips").css("display","block");
              $("#Add-Active-ProJect .Result_Tips").html("<div class=\"alert alert-success\">添加成功</div>");
              $('#Add-Active-ProJect').modal('hide'); // 添加项目成功后，关闭当前编辑器
              $('#Edi-ActiveZjy').modal('show'); //打开项目列表
              var edi_active_id = data.active_id;
              GetProJectList(edi_active_id); // 刷新项目列表

            }else{
              $("#Add-Active-ProJect .Result_Tips").css("display","block");
              $("#Add-Active-ProJect .Result_Tips").html("<div class=\"alert alert-danger\">"+data.msg+"</div>");
            }
          },
          error : function(data) {
            // 创建失败
            alert("添加失败");
          },
        beforeSend:function(data){
          // 正在创建
          $("#Add-Active-ProJect .Result_Tips").css("display","block");
          $("#Add-Active-ProJect .Result_Tips").html("<div class=\"alert alert-danger\">正在添加项目...</div>");
        }
      });
      // 延迟2秒隐藏全局信息提示框
      setTimeout('closesctips()', 2000);
  }

  // 获取编辑项目详情
  function Edi_ActiveZjy_Project(event){

  	// 隐藏上一个弹窗
  	$('#Edi-ActiveZjy').modal('hide');

    // 获得项目id
    var project_id = event.id;

    // 获取需要编辑的项目内容
    $.ajax({
      type: "GET",
      url: "GetProJectInfo.php?projectid="+project_id,
      success: function (data) {

        // 获得项目、活动内容
        var project_text = data.text;
        var project_pic = data.pic;
        var active_title = data.active_title;
        var active_id = data.active_id;

        // 将内容设置到富文本编辑器
        EdiFwbHtmlContent.setValue(project_text);
        $("#Edi-Active-ProJect .modal-footer .project_pic_con").attr("value",project_pic);
        $("#Edi-Active-ProJect .modal-footer .project_text_con").attr("value",project_text);
        $("#Edi-Active-ProJect .modal-footer .project_id_con").attr("value",project_id);
        $("#Edi-Active-ProJect .modal-body .active_title_con").attr("value",active_title);
        $("#Edi-Active-ProJect .modal-footer .active_title_con").attr("value",active_title);
        $("#Edi-Active-ProJect .modal-footer .active_id_con").attr("value",active_id);
      },
      error: function() {
        $("#Result_Tips").css("display","block");
        $("#Result_Tips").html("<div class=\"alert alert-danger\">服务器发生错误</div>");
        setTimeout('closesctips()', 2000);
      }
    });
  }


  // 提交编辑项目
  function Edi_ActiveZjy_Project_Action(){

  	// 获得更新的活动标题
  	var new_active_title = $("#Edi-Active-ProJect .modal-body .active_title_con").val();
  	$("#Edi-Active-ProJect .modal-footer .active_title_con").attr("value",new_active_title);
    $.ajax({
      type: "POST",
      url: "Edi-ActiveProject-do.php",
      data: $('#Edi_ActiveZjy_Project_Action').serialize(),
      success: function (data) {
      	if (data.result == 100) {
      		$("#Result_Tips").css("display","block");
        	$("#Result_Tips").html("<div class=\"alert alert-success\">"+data.msg+"</div>");
        	// 关闭当前编辑器
        	$('#Edi-Active-ProJect').modal('hide');
        	// 打开项目列表
        	$('#Edi-ActiveZjy').modal('show');
        	// 更新当前项目列表
        	var edi_active_id = data.active_id;
        	GetProJectList(edi_active_id);
      	}else{
      		$("#Result_Tips").css("display","block");
        	$("#Result_Tips").html("<div class=\"alert alert-danger\">"+data.msg+"</div>");
      	}
      },
      error: function() {
        $("#Result_Tips").css("display","block");
        $("#Result_Tips").html("<div class=\"alert alert-danger\">服务器发生错误</div>");
      }
    });
    setTimeout('closesctips()', 2000);
  }


  // 删除项目
  function Del_ActiveZjy_Project(event){
    // 获得项目id
    var del_project_id = event.id;
    $.ajax({
      type: "GET",
      url: "Del-ProJect-do.php?projectid="+del_project_id,
      success: function (data) {
      	if (data.result == 100) {
      		$("#Result_Tips").css("display","block");
        	$("#Result_Tips").html("<div class=\"alert alert-success\">"+data.msg+"</div>");
        	// 更新当前项目列表
        	var edi_active_id = data.active_id;
        	GetProJectList(edi_active_id);
      	}else{
      		$("#Result_Tips").css("display","block");
        	$("#Result_Tips").html("<div class=\"alert alert-danger\">服务器发生错误</div>");
      	}
      },
      error: function() {
        $("#Result_Tips").css("display","block");
        $("#Result_Tips").html("<div class=\"alert alert-danger\">服务器发生错误</div>");
      }
    });
    setTimeout('closesctips()', 2000);
  }


  // 获得需要删除的活动中间页的id
  function Get_ActiveZjy_Id(event){
  	var get_active_id = event.id;
  	$("#delzjy_model .modal-dialog .modal-footer").html("<button type=\"button\" class=\"btn-zdy\" data-dismiss=\"modal\" id="+get_active_id+" onclick=\"Del_ActiveZjy(this);\">确定删除</button>");
  }

  // 删除活动页
  function Del_ActiveZjy(event){
      
    // 获得活动中间页id
    var del_active_id = event.id;
    $.ajax({
      type: "GET",
      url: "Del-ActiveZjy-do.php?activeid="+del_active_id,
      success: function (data) {
      	if (data.result == 100) {
      		$("#Result_Tips").css("display","block");
        	$("#Result_Tips").html("<div class=\"alert alert-success\">"+data.msg+"</div>");
        	location.reload();
      	}else{
      		$("#Result_Tips").css("display","block");
        	$("#Result_Tips").html("<div class=\"alert alert-danger\">服务器发生错误</div>");
      	}
      },
      error: function() {
        $("#Result_Tips").css("display","block");
        $("#Result_Tips").html("<div class=\"alert alert-danger\">服务器发生错误</div>");
      }
    });
    setTimeout('closesctips()', 2000);
  }
</script>
</body>
</html>