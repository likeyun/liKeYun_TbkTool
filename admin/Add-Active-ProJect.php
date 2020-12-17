<?php
header("Content-type:application/json");

// 获得前端POST过来的参数
$active_text = trim($_POST["active_text"]);
$active_pic = trim($_POST["active_pic"]);
$active_id = trim($_POST["active_id"]);
$project_copy_status = trim($_POST["project_copy_status"]);

// 验证登录状态
session_start();
if(isset($_SESSION["tbktools.admin"])){
	// 过滤空值
	if (empty($active_text)) {
		$result = array(
			"result" => "101",
			"msg" => "活动内容不得为空"
		);
	}else if (empty($project_copy_status)) {
		$result = array(
			"result" => "102",
			"msg" => "请选择一键复制开关"
		);
	}else{
		// 引入数据库配置
		include '../Db_Connect.php';

		// 连接数据库
		$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
		mysqli_query($conn, "SET NAMES UTF-8");

		// 项目id
		$active_project_id = rand(100000,999999);

		// 插入数据库
		$sql = "INSERT INTO tbk_active_project (active_text, active_pic, active_project_id, active_id, active_copy) VALUES ('$active_text','$active_pic','$active_project_id','$active_id','$project_copy_status')";

		// 验证插入结果
		if ($conn->query($sql) === TRUE) {
		    $result = array(
				"result" => "100",
				"msg" => "添加成功",
				"active_id" => $active_id
			);
		} else {
		    $result = array(
				"result" => "110",
				"msg" => "添加失败，数据库配置发生错误"
			);
		}

		// 断开数据库连接
		$conn->close();
	}
}else{
	$result = array(
		"result" => "111",
		"msg" => "未登录"
	);
}
// 返回JSON
echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>