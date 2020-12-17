<?php
header("Content-type:application/json");

// 获得设置对象
$setobj = trim($_POST["setobj"]);
$setval = trim($_POST["setval"]);

// 验证登录状态
session_start();
if(isset($_SESSION["tbktools.admin"])){
	// 过滤空值
	if (empty($setval)) {
		$result = array(
			"result" => "101",
			"msg" => "设置项不得为空"
		);
	}else{
		// 引入数据库配置
		include '../Db_Connect.php';

		// 连接数据库
		$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
		mysqli_query($conn, "SET NAMES UTF-8");

		// 更新数据库
		$sql = "UPDATE tbk_gzh_set SET gzh_set_val='$setval' WHERE gzh_set_obj='$setobj'";
		
		// 验证插入结果
		if ($conn->query($sql) === TRUE) {
		    $result = array(
				"result" => "100",
				"msg" => "设置成功，正在返回上一页"
			);
		} else {
		    $result = array(
				"result" => "104",
				"msg" => "设置失败，数据库配置发生错误"
			);
		}

		// 断开数据库连接
		$conn->close();
	}
}else{
	$result = array(
		"result" => "105",
		"msg" => "未登录"
	);
}
// 返回JSON
echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>