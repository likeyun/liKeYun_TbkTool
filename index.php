<!DOCTYPE html>
<html>
<?php
// 开源作者：TANKING
// 如有遇到安装问题，请加入微信群
// 微信群进群地址：http://pic.iask.cn/fimg/591377922798.jpg
header("Content-type:text/html;charset=utf-8");

// 获得中间页ID
$zid = trim($_GET["zid"]);

// 引入数据库配置
include './Db_Connect.php';

// 连接数据库
$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
mysqli_query($conn, "SET NAMES UTF-8");

// 获得当前zid的相关信息
$sql_tp = "SELECT * FROM tbk_zjy WHERE zjy_id = '$zid'";
$result_tp = $conn->query($sql_tp);
if ($result_tp->num_rows > 0) {
	while($row_tp = $result_tp->fetch_assoc()) {
		$zjy_template = $row_tp["zjy_template"]; // 模板
		$zjy_long_title = $row_tp["zjy_long_title"]; // 长标题
		$zjy_short_title = $row_tp["zjy_short_title"]; // 短标题
		$zjy_yprice = $row_tp["zjy_yprice"]; // 原价
		$zjy_qhprice = $row_tp["zjy_qhprice"]; // 券后加
		$zjy_tkl = $row_tp["zjy_tkl"]; // 淘口令
		$zjy_cover = $row_tp["zjy_cover"]; // 主图
		$zjy_pv = $row_tp["zjy_pv"]; // 访问量
		$AppRedUrl = $row_tp["AppRedUrl"]; // 跳转App的链接
	}
	// 更新访问量
	mysqli_query($conn,"UPDATE tbk_zjy SET zjy_pv=zjy_pv+1 WHERE zjy_id='$zid'");
	
	// iphone开启跳转到app
    $useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
    $is_iphone = (strpos($useragent, 'iphone')) ? true : false;

	// 根据设置的模板进行显示
	if ($zjy_template == "tp1") {
		// 模板一
		echo '<body><head>
		<title>粉丝福利购</title>
		<link rel="stylesheet" type="text/css" href="./css/tp1.css">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0,viewport-fit=cover">
		</head>';
		echo '<!-- 顶部 -->
		<div id="top">'.$zjy_short_title.'</div>

		<!-- 主图 -->
		<div id="zhutu">
			<img src="'.$zjy_cover.'"/>
		</div>

		<!-- 产品标题 -->
		<div id="title_con">
			<span class="by">包邮</span>
			<span class="title">'.$zjy_long_title.'</span>
		</div>

		<!-- 价格信息 -->
		<div id="title_con">
			<span class="qhj">券后价</span>
			<span class="tofee">¥'.$zjy_qhprice.'&nbsp;&nbsp;<span class="yj">原价 <s> ¥'.$zjy_yprice.' </s></span><span style="float:right;color:#999;font-size:14px;margin-top:-20px;">已有'.$zjy_pv.'人购买</span></span>
		</div>';
    
        if($is_iphone){
            echo '
    		<!-- tkl -->
    		<div id="tkl">
    			<div class="kl" id="tkl_text">1'.$zjy_tkl.':/</div>
    			<a href="'.$AppRedUrl.'"><div class="copy" id="copy">立即复制</div></a>
    		</div>';
        }else{
            echo '
    		<!-- tkl -->
    		<div id="tkl">
    			<div class="kl" id="tkl_text">1'.$zjy_tkl.':/</div>
    			<div class="copy" id="copy">立即复制</div>
    		</div>';
        }
		
		echo '
		<!-- 领取方法 -->
		<p style="color: #999;text-align: center;font-size: 15px;">复制淘口令 -> 打开手机淘宝APP即可</p>
		<p style="color: #FF5000;text-align: center;font-size: 15px;margin-top:20px;">（限时限量，商家刷单活动价，手慢无）</p>

		<!-- 复制成功提示 -->
		<div id="copytips"><p class="success"><br/>复制成功<br/>打开淘宝APP</p></div>';
	}else if ($zjy_template == "tp2") {
		echo '<body><head>
		<title>'.$zjy_short_title.'</title>
		<link rel="stylesheet" type="text/css" href="./css/tp2.css">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0,viewport-fit=cover">
		</head>';
		echo '<!-- 主图 -->
		<div id="zhutu">
			<img src="'.$zjy_cover.'"/>
		</div>

		<!-- 长标题 -->
		<div id="long_title">'.$zjy_long_title.'</div>

		<!-- 价格信息 -->
		<div id="price_msg">
			<i><span class="qhj">券后价 ¥'.$zjy_qhprice.'</span></i>
			<i><span class="yj">原价<s> ¥'.$zjy_yprice.'</s></span></i>
		</div>';

		if($is_iphone){
            echo '
    		<!-- tkl -->
    		<div id="tkl">
    			<div class="kl" id="tkl_text">1'.$zjy_tkl.':/</div>
    			<a href="'.$AppRedUrl.'" style="text-decoration:none;"><div class="copy" id="copy">立即复制</div></a>
    		</div>';
        }else{
            echo '
    		<!-- tkl -->
    		<div id="tkl">
    			<div class="kl" id="tkl_text">1'.$zjy_tkl.':/</div>
    			<div class="copy" id="copy">立即复制</div>
    		</div>';
        }

		echo '
		<!-- 领取方法 -->
		<p style="color: #999;text-align: center;font-size: 15px;">复制淘口令 -> 打开手机淘宝APP即可</p><br/><br/>

		<!-- 复制成功提示 -->
		<div id="copytips"><p class="success"><br/>复制成功<br/>打开淘宝APP</p></div>';
	}else if ($zjy_template == "tp3") {
		echo '<body><head>
		<title>'.$zjy_short_title.'</title>
		<link rel="stylesheet" type="text/css" href="./css/tp3.css">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0,viewport-fit=cover">
		</head>';
		echo '<!-- 主图 -->
		<div id="zhutu">
			<img src="'.$zjy_cover.'"/>
		</div>

		<!-- 价格信息 -->
		<div id="price_msg">
			<i><span class="qhj">¥'.$zjy_qhprice.'</span></i>
			<i><span class="yj">原价<s> ¥'.$zjy_yprice.'</s></span></i>
		</div>

		<!-- 长标题 -->
		<div id="long_title">'.$zjy_long_title.'</div>';
		
		if($is_iphone){
            echo '
    		<!-- tkl -->
    		<div id="tkl">
    			<div class="kl">
    				<div class="tkltips">复制这个淘口令</div>
    				<div class="tkltext" id="tkl_text">1'.$zjy_tkl.':/</div>
    			</div>
    			<a href="'.$AppRedUrl.'" style="text-decoration:none;"><div class="copy" id="copy">立即复制</div></a>
    		</div>';
        }else{
            echo '
    		<!-- tkl -->
    		<div id="tkl">
    			<div class="kl">
    				<div class="tkltips">复制这个淘口令</div>
    				<div class="tkltext" id="tkl_text">1'.$zjy_tkl.':/</div>
    			</div>
    			<div class="copy" id="copy">立即复制</div>
    		</div>';
        }

		echo '
		<!-- 领取方法 -->
		<p style="color: #999;text-align: center;font-size: 15px;">复制淘口令 -> 打开手机淘宝APP即可</p>

		<!-- 复制成功提示 -->
		<div id="copytips"><p class="success"><br/>复制成功<br/>打开淘宝APP</p></div>';
	}
}
?>
</body>
<script src="./js/jquery.min.js"></script>
<script type="text/javascript">
	function hide(){
		$("#copytips .success").css("display","none");
	}

	function copyArticle(event){
	  const range = document.createRange();
	  range.selectNode(document.getElementById('tkl_text'));
	  const selection = window.getSelection();
	  if(selection.rangeCount > 0) selection.removeAllRanges();
	  selection.addRange(range);
	  document.execCommand('copy');
	  $("#copytips .success").css("display","block");
	  $("#tkl .copy").text("已复制");
	  setTimeout('hide()', 2000);
	  
	}

	window.onload = function () {
	  var obt = document.getElementById("copy");
	  obt.addEventListener('click', copyArticle, false);
	}
</script>
</html>