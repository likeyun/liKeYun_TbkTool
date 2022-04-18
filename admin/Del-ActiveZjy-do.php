<?php
header("Content-type:application/json");
session_start();
if(isset($_SESSION["tbktools.admin"])){

	// 引入数据库配置
	include '../Db_Connect.php';

	// 连接数据库
	$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
	mysqli_query($conn, "SET NAMES UTF-8");

	// 获取活动id
	$active_id = trim($_GET["activeid"]);

	if(empty($active_id)){
		$result = array(
			"result" => "101",
			"msg" => "非法请求"
		);
	}else{

		// 删除数据
		mysqli_query($conn,"DELETE FROM tbk_active_zjy WHERE active_id='$active_id'");
		mysqli_query($conn,"DELETE FROM tbk_active_project WHERE active_id='$active_id'");
		$result = array(
			"result" => "100",
			"msg" => "已删除活动"
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