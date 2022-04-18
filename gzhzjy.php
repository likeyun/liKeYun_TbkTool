<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0,viewport-fit=cover">
    <style type="text/css">
        *{
            margin:0;
            padding:0;
            font-family: -apple-system-font,BlinkMacSystemFont,"Helvetica Neue","PingFang SC","Hiragino Sans GB","Microsoft YaHei UI","Microsoft YaHei",Arial,sans-serif;
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
        #title{
        	width: 80%;
        	margin:80px auto 0;
        	border:3px dotted #f77062;
        	border-radius: 8px;
        	padding: 15px 15px;
        }
        #tkl{
        	width: 80%;
        	margin:20px auto 0;
        	border:3px dotted #f77062;
        	border-radius: 8px;
        	padding: 15px 15px;
        }
        #copy{
        	width: 90%;
        	height: 50px;
        	background-image: linear-gradient(to bottom right, #f77062, #fe5196);
        	border-radius: 6px;
        	text-align: center;
        	line-height: 50px;
        	font-size: 17px;
        	font-weight: bold;
        	margin:20px auto;
        	color: #fff;
        	-webkit-tap-highlight-color:rgba(255,0,0,0);
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

// 获得zid
$zid = trim($_GET['zid']);

// 没有zid参数传进来
if (empty($zid)) {
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

// 查询数据
$sql = "SELECT * FROM tbk_gzh_zjy WHERE zid='$zid'";
$result = $conn->query($sql);

// 遍历并输出数据
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $title = $row["title"];
        $tkl = $row["tkl"];
        $s_title = mb_substr($title,1,10,'utf-8'); // 短标题
    }
    // 页面title
    echo '<title>粉丝福利购</title>';
    // 输出HTML
    echo '<!-- 顶部标题 -->
		  <div id="top">'.$s_title.'</div>

		  <!-- 标题 -->
		  <div id="title">'.$title.'</div>

		  <!-- 淘口令 -->
		  <div id="tkl">'.$tkl.'</div>

		  <!-- 复制按钮 -->
		  <div id="copy">一键复制</div>

		  <!-- TIPS -->
		  <p id="tips">复制上方淘口令 -> 打开手机淘宝APP</p>

		  <!-- 复制提示 -->
		  <div id="copytips"><p class="success"><br/>复制成功<br/>打开淘宝APP</p></div>';
} else {
    echo '<title>链接失效</title>';
    echo '<div style="width:100%;height:30px;position:fixed;top:0;background:#fff;"></div>
         <div id="notfoundicon">
         <img src="./images/notfound.png" />
         </div>
         <p style="text-align:center;font-size:17px;color:#999;">该链接已被管理员删除</p>';
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

	// 复制函数
	function copyArticle(event){
	  const range = document.createRange();
	  range.selectNode(document.getElementById('tkl'));
	  const selection = window.getSelection();
	  if(selection.rangeCount > 0) selection.removeAllRanges();
	  selection.addRange(range);
	  document.execCommand('copy');
	  // 点击复制之后，显示已复制的提示
	  document.getElementById("copytips").style.display="block";
	  // 点击复制之后，修改复制按钮的文字
	  document.getElementById("copy").innerHTML = "已复制";
	  // 2秒后隐藏提示
	  setTimeout('hidetips()', 2000);
	  
	}

	// 加载复制事件
	window.onload = function () {
	  var obt = document.getElementById("copy");
	  obt.addEventListener('click', copyArticle, false);
	}

    // 禁止用户下拉
    document.body.addEventListener('touchmove', function (e) {
        e.preventDefault()
    },{passive: false})

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