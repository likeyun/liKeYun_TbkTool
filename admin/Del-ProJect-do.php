<?php
header("Content-type:application/json");
session_start();
if(isset($_SESSION["tbktools.admin"])){

	// 引入数据库配置
	include '../Db_Connect.php';

	// 连接数据库
	$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
	mysqli_query($conn, "SET NAMES UTF-8");

	// 获取活动id和项目id
	$project_id_str = trim($_GET["projectid"]);
	$active_id = substr($project_id_str,strripos($project_id_str,"@")+1);
	$project_id = substr($project_id_str,0,strrpos($project_id_str,"@"));

	if(empty($project_id)){
		$result = array(
			"result" => "101",
			"msg" => "非法请求"
		);
	}else{

		// 删除数据
		mysqli_query($conn,"DELETE FROM tbk_active_project WHERE active_project_id='$project_id'");
		$result = array(
			"result" => "100",
			"msg" => "已删除项目",
			"active_id" => $active_id
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