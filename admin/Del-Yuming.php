<?php
header("Content-type:application/json");
session_start();
if(isset($_SESSION["tbktools.admin"])){

	// 引入数据库配置
	include '../Db_Connect.php';

	// 连接数据库
	$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
	mysqli_query($conn, "SET NAMES UTF-8");

	// 获取yumingid
	$yumingid = trim($_GET["yumingid"]);

	if(empty($yumingid)){
		$result = array(
			"result" => "101",
			"msg" => "非法请求"
		);
	}else{
		// 删除数据
		mysqli_query($conn,"DELETE FROM tbk_yuming WHERE id='$yumingid'");
		$result = array(
			"result" => "100",
			"msg" => "已删除"
		);
	}
}else{
	$result = array(
		"result" => "102",
		"msg" => "未登录"
	);
}
// 返回JSON
echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>