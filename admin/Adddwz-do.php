<?php
header("Content-type:application/json");

// 获得短网址API URL和标题
$dwzapi = trim($_POST["dwzapi"]);
$dwztitle = trim($_POST["dwztitle"]);

// 验证登录状态
session_start();
if(isset($_SESSION["tbktools.admin"])){
	// 过滤空值
	if (empty($dwzapi)) {
		$result = array(
			"result" => "101",
			"msg" => "API URL不得为空"
		);
	}else if(empty($dwztitle)){
		$result = array(
			"result" => "104",
			"msg" => "短网址标题不得为空"
		);
	}else if(strpos($dwzapi,'http') !== false){

		// 引入数据库配置
		include '../Db_Connect.php';

		// 连接数据库
		$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
		mysqli_query($conn, "SET NAMES UTF-8");

		// 更新数据库
		$sql = "INSERT INTO tbk_dwzapi (dwzapi, dwztitle) VALUES ('$dwzapi', '$dwztitle')";
		
		// 验证插入结果
		if ($conn->query($sql) === TRUE) {
		    $result = array(
				"result" => "100",
				"msg" => "添加成功，正在返回上一页"
			);
		} else {
		    $result = array(
				"result" => "103",
				"msg" => "添加失败，数据库配置发生错误"
			);
		}

		// 断开数据库连接
		$conn->close();

	}else{
		$result = array(
			"result" => "104",
			"msg" => "请按照规定的格式填写短网址API URL"
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