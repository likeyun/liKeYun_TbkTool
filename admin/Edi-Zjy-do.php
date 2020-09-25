<?php
header("Content-type:application/json");

// 获得前端POST过来的参数
$zjy_long_title = trim($_POST["zjy_long_title"]);
$zjy_short_title = trim($_POST["zjy_short_title"]);
$zjy_yprice = trim($_POST["zjy_yprice"]);
$zjy_qhprice = trim($_POST["zjy_qhprice"]);
$zjy_tkl = trim($_POST["zjy_tkl"]);
$zjy_cover = trim($_POST["zjy_cover"]);
$zjy_template = trim($_POST["zjy_template"]);
$zid = trim($_POST["zid"]);

// 验证登录状态
session_start();
if(isset($_SESSION["tbktools.admin"])){
	// 过滤空值
	if (empty($zjy_long_title)) {
		$result = array(
			"result" => "101",
			"msg" => "长标题不得为空"
		);
	}else if (empty($zjy_short_title)) {
		$result = array(
			"result" => "102",
			"msg" => "短标题不得为空"
		);
	}else if (empty($zjy_yprice)) {
		$result = array(
			"result" => "103",
			"msg" => "原价不得为空"
		);
	}else if (empty($zjy_qhprice)) {
		$result = array(
			"result" => "104",
			"msg" => "券后价不得为空"
		);
	}else if (empty($zjy_tkl)) {
		$result = array(
			"result" => "105",
			"msg" => "淘口令不得为空"
		);
	}else if (empty($zjy_cover)) {
		$result = array(
			"result" => "106",
			"msg" => "主图不得为空"
		);
	}else if (empty($zid)) {
		$result = array(
			"result" => "107",
			"msg" => "非法操作"
		);
	}else if (empty($zjy_template)) {
		$result = array(
			"result" => "108",
			"msg" => "请选择中间页模板"
		);
	}else{
		// 引入数据库配置
		include '../Db_Connect.php';

		// 连接数据库
		$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
		mysqli_query($conn, "SET NAMES UTF-8");

		// 更新数据库
		$sql = "UPDATE tbk_zjy SET zjy_long_title='$zjy_long_title',zjy_short_title='$zjy_short_title',zjy_yprice='$zjy_yprice',zjy_qhprice='$zjy_qhprice',zjy_tkl='$zjy_tkl',zjy_cover='$zjy_cover',zjy_template='$zjy_template' WHERE zjy_id='$zid'";
		
		// 验证插入结果
		if ($conn->query($sql) === TRUE) {
		    $result = array(
				"result" => "100",
				"msg" => "更新成功，正在返回首页"
			);
		} else {
		    $result = array(
				"result" => "109",
				"msg" => "更新失败，数据库配置发生错误"
			);
		}

		// 断开数据库连接
		$conn->close();
	}
}else{
	$result = array(
		"result" => "110",
		"msg" => "未登录"
	);
}
// 返回JSON
echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>