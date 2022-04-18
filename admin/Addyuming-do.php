<?php
header("Content-type:application/json");

// 获得域名
$yuming = trim($_POST["yuming"]);

// 验证登录状态
session_start();
if(isset($_SESSION["tbktools.admin"])){
	// 过滤空值
	if (empty($yuming)) {
		$result = array(
			"result" => "101",
			"msg" => "域名不得为空"
		);
	}else if(strpos($yuming,'http') !== false){

		// 验证最后一个字符是不是/符号
		$checkUrl = substr($yuming, -1);
		if ($checkUrl == '/') {
			$result = array(
				"result" => "105",
				"msg" => "不能以' / '结束",
			);
		}else{
			// 引入数据库配置
			include '../Db_Connect.php';

			// 连接数据库
			$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
			mysqli_query($conn, "SET NAMES UTF-8");
			$sql = "INSERT INTO tbk_yuming (yuming,type) VALUES ('$yuming','落地域名')";
			
			// 验证插入结果
			if ($conn->query($sql) === TRUE) {
			    $result = array(
					"result" => "100",
					"msg" => "添加成功"
				);
			} else {
			    $result = array(
					"result" => "103",
					"msg" => "添加失败，数据库配置发生错误，请查看Addyuming-do.php是否存在服务错误，可F12打开开发者工具选择NetWork->Preview查看网络请求进行排查。"
				);
			}

			// 断开数据库连接
			$conn->close();
		}

	}else{
		$result = array(
			"result" => "104",
			"msg" => "请按照规定的格式填写域名"
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