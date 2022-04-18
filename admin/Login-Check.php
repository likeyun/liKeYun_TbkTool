<?php
header("Content-type:application/json");

// 数据库配置
include '../Db_Connect.php';

$user = trim($_POST["user"]);
$pwd = trim($_POST["pwd"]);

if($user == "" && $pwd == ""){
	$result = array(
		"result" => "101",
		"msg" => "账号和密码不得为空"
	);
}else if($user == ""){
	$result = array(
		"result" => "102",
		"msg" => "账号不得为空"
	);
}else if ($pwd == "") {
	$result = array(
		"result" => "103",
		"msg" => "密码不得为空"
	);
}else{
	
	// 创建数据库连接
	$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
	if ($conn->connect_error) {
	    $result = array(
			"result" => "106",
			"msg" => "数据库连接失败"
		);
	} 
	
	// 验证账号和密码
	$sql_checkuser = "SELECT * FROM tbk_user WHERE user_name = '$user' AND user_pwd = '$pwd'";
	$result_checkuser=mysqli_query($conn,$sql_checkuser);
	// 返回记录
	$row_user=mysqli_num_rows($result_checkuser);
	if ($row_user) {
		// 如果账号和密码都对上，直接登录
		session_start();
		$_SESSION['tbktools.admin'] = $user;
		$result = array(
			"result" => "100",
			"msg" => "登录成功"
		);	
	}else{
		// 否则返回账号或密码错误
		$result = array(
			"result" => "104",
			"msg" => "账号或密码错误"
		);
	}
	// 释放结果集
    mysqli_free_result($result_checkuser);
    // 断开数据库连接
	$conn->close();
}
echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>