<?php
header("Content-type:application/json");

// 获得设置对象
$setobj = trim($_POST["setobj"]);

// 验证登录状态
session_start();
if(isset($_SESSION["tbktools.admin"])){
	// 过滤空值
	if (empty($setobj)) {
		$result = array(
			"result" => "101",
			"msg" => "非法提交"
		);
	}else{
		// 引入数据库配置
		include '../Db_Connect.php';

		// 连接数据库
		$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
		mysqli_query($conn, "SET NAMES UTF-8");

		// 验证设置对象
		if ($setobj == 'pid') {
			$pid = trim($_POST["set_pid"]);
			if (empty($pid)) {
				$result = array(
					"result" => "102",
					"msg" => "PID不得为空"
				);
			}else{
				// 更新数据库
				$sql = "UPDATE tbk_set SET Set_Val='$pid' WHERE Set_Obj='zjy_pid'";
				
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
			}
		}else if ($setobj == 'tbname') {
			$tbname = trim($_POST["set_tbname"]);
			if (empty($tbname)) {
				$result = array(
					"result" => "103",
					"msg" => "淘宝账号不得为空"
				);
			}else{
				// 更新数据库
				$sql = "UPDATE tbk_set SET Set_Val='$tbname' WHERE Set_Obj='zjy_tbname'";
				
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
			}
		}else if ($setobj == 'appkey') {
			$appkey = trim($_POST["set_appkey"]);
			if (empty($appkey)) {
				$result = array(
					"result" => "105",
					"msg" => "AppKey不得为空"
				);
			}else{
				// 更新数据库
				$sql = "UPDATE tbk_set SET Set_Val='$appkey' WHERE Set_Obj='zjy_appkey'";
				
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
			}
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