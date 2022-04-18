<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="./js/jquery.min.js"></script>
    <script src="./js/clipboard.min.js"></script>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0,viewport-fit=cover">
    <style type="text/css">
        *{
            margin:0;
            padding:0;
            font-family: -apple-system-font,BlinkMacSystemFont,"Helvetica Neue","PingFang SC","Hiragino Sans GB","Microsoft YaHei UI","Microsoft YaHei",Arial,sans-serif;
        }
        body{
            background: #f3f3f3;
        }
        #top{
        	width: 100%;
        	height: 50px;
        	background-image: linear-gradient(to right, #ff9569 0%, #e92758 100%);
        	position: fixed;
        	top: 0;
        	text-align: center;
        	line-height: 50px;
        	font-size: 16px;
        	color: #fff;
        	font-weight: bold;
        }
        #pic{
            width: 88%;
            margin:0 auto 10px;
            /*background: #f00;*/
        }
        #pic img{
            width: 100%;
            border-radius: 10px;
            overflow: hidden;
        }
        /*项目内容区*/
        #project_content{
            width: 90%;
            background: #fff;
            padding:22px 0;
            border-radius: 10px;
            margin:18px auto 0;
            box-shadow: 0 0 5px #eee;
        }
        #project_content .project_text{
            width: 80%;
            background: #f5f5f5;
            border-radius: 10px;
            margin:0 auto;
            padding:20px 20px;
        }
        #copy{
        	width: 90%;
        	height: 45px;
        	background-image: linear-gradient(to bottom right, #f77062, #fe5196);
        	border-radius: 6px;
        	text-align: center;
        	line-height: 45px;
        	font-size: 17px;
        	font-weight: bold;
        	margin:15px auto 5px;
        	color: #fff;
        	-webkit-tap-highlight-color:rgba(255,0,0,0);
            border:none;
            display: block;
        }
        #tips{
        	text-align: center;
        	margin-top: 30px;
        	font-size: 15px;
        	color: #999;
        }
        #copytips{
			display: none;
        }
        #copytips .success{
			width: 160px;
			height: 85px;
            line-height: 85px;
			background: rgba(0,0,0,0.5);
			margin:20px auto;
			color: #fff;
			font-size: 17px;
			text-align: center;
			border-radius: 10px;
			position: fixed;
			top: 260px;
			left: 0;
			right: 0;
		}
        #notfoundicon{
            width: 130px;
            height: 130px;
            margin:80px auto 20px;
        }
        #notfoundicon img{
            width: 130px;
            height: 130px;
        }
    </style>
</head>
<body>
<?php
header("Content-Type:text/html;charset=utf-8");

// 获得activeid
$activeid = trim($_GET['activeid']);

// 没有activeid参数传进来
if (empty($activeid)) {
	die("参数错误");
}

// 引入数据库配置
include './Db_Connect.php';
 
// 创建连接
$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 更新访问量
mysqli_query($conn,"UPDATE tbk_active_zjy SET active_pv=active_pv+1 WHERE active_id='$activeid'");

// 获取当前中间页的标题
$sql_title = "SELECT * FROM tbk_active_zjy WHERE active_id='$activeid'";
$result_title = $conn->query($sql_title);
if ($result_title->num_rows > 0) {
    while($row_title = $result_title->fetch_assoc()) {
        $zjy_title = $row_title["active_title"];
    }
}else{
    echo '<title>链接失效</title>';
    echo '<div style="width:100%;height:30px;position:fixed;top:0;background:#fff;"></div>
         <div id="notfoundicon">
         <img src="./images/notfound.png" />
         </div>
         <p style="text-align:center;font-size:17px;color:#999;">该链接已被管理员删除</p>';
    exit;
}

// 查询数据
$sql = "SELECT * FROM tbk_active_project WHERE active_id='$activeid' ORDER BY ID ASC";
$result = $conn->query($sql);

// 遍历并输出数据
if ($result->num_rows > 0) {
    echo '<div id="top">'.$zjy_title.'</div>';
    echo '<title>活动详情</title>';
    echo '<div style="width:100%;height:50px;margin:0 auto;"></div>';
    while($row = $result->fetch_assoc()) {
        $id = $row["id"];
        $pro_id = "pro_".$id;
        $active_text = $row["active_text"];
        $active_pic = $row["active_pic"];
        $copy_status = $row["active_copy"];

        // 遍历输出所有项目
        echo '<div id="project_content">';
        		if (trim(empty($active_pic))) {
		        	// 
		        }else{
		        	echo '<div id="pic"><img src="'.$active_pic.'"/></div>';
		        }
                echo '<div class="project_text" id="'.$pro_id.'">'.$active_text.'</div>';
                // 获取复制按钮开启状态
                if ($copy_status == 1) {
                	echo '<button id="copy" data-clipboard-action="copy" data-clipboard-target="#'.$pro_id.'">一键复制</button>';
                }else{
                	// 不显示复制按钮
                }
        echo '</div>';
    }
        // 复制提示
        echo '<div id="copytips"><p class="success">复制成功</p></div>';

        echo "<br/><br/><br/>";
} else {
    echo '<title>温馨提示</title>';
    echo '<div id="notfoundicon">
         <img src="./images/notfound.png" />
         </div>
         <p style="text-align:center;font-size:17px;color:#999;">暂无活动项目</p>';
    exit;
}

// 断开数据库连接
$conn->close();
?>
</body>

<!-- 原生JS复制 -->
<script type="text/javascript">
	// 隐藏copytips
	function hidetips(){
		document.getElementById("copytips").style.display="none";
	}

    // clipboard
    $(document).ready(function(){       
        var clipboard = new Clipboard('#copy');    
        clipboard.on('success', function(e) {
            document.getElementById("copytips").style.display="block";
            setTimeout('hidetips()', 2000);
            e.clearSelection();
        });    
    }); 

    //隐藏分享等按钮
    function onBridgeReady() {
        WeixinJSBridge.call('hideOptionMenu');
    }
    if (typeof WeixinJSBridge == "undefined") {
        if (document.addEventListener) {
            document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
        } else if (document.attachEvent) {
            document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
            document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
        }
    } else {
        onBridgeReady();
    }
</script>
</html>