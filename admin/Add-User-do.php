<?php
header("Content-type:application/json");

$user = trim($_POST["user_name"]);
$pwd = trim($_POST["user_pwd"]);
$email = trim($_POST["user_email"]);
$pid = trim($_POST["pid"]);
$appkey = trim($_POST["appkey"]);
$tbname = trim($_POST["tbname"]);

// 账号不能存在特殊符号
$tsfh = preg_match("/[\'.,:;*?~`!@#$%^&+=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$user);

// 账号和密码不能存在中文
$userczzw = preg_match('/[\x{4e00}-\x{9fa5}]/u', $user);
$pwdczzw = preg_match('/[\x{4e00}-\x{9fa5}]/u', $pwd);

if($user == "" && $pwd == "" && $email == "" && $pid == ""){
	$result = array(
		"code" => "101",
		"msg" => "请完善所有必填项"
	);
}else if($user == "" || empty($user)){
	$result = array(
		"code" => "102",
		"msg" => "账号不得为空"
	);
}else if ($pwd == "" || empty($pwd)) {
	$result = array(
		"code" => "103",
		"msg" => "密码不得为空"
	);
}else if ($pid == "" || empty($pid)) {
	$result = array(
		"code" => "105",
		"msg" => "PID不得为空"
	);
}else if ($appkey == "" || empty($appkey)) {
	$result = array(
		"code" => "115",
		"msg" => "appkey不得为空"
	);
}else if ($email == "" || empty($email)) {
	$result = array(
		"code" => "104",
		"msg" => "邮箱不得为空"
	);
}else if ($tbname == "" || empty($tbname)) {
	$result = array(
		"code" => "114",
		"msg" => "淘宝账号不得为空"
	);
}else if (strlen($user) < 6) {
	$result = array(
		"code" => "106",
		"msg" => "账号长度不得小于6个字符"
	);
}else if (strlen($pwd) < 8) {
	$result = array(
		"code" => "107",
		"msg" => "密码长度不得小于8个字符"
	);
}else if ($tsfh) {
	$result = array(
		"code" => "108",
		"msg" => "账号不能存在特殊字符"
	);
}else if($userczzw>0){
	$result = array(
		"code" => "109",
		"msg" => "账号不能存在中文"
	);
}else if($pwdczzw>0){
	$result = array(
		"code" => "110",
		"msg" => "密码不能存在中文"
	);
}else{

	// 连接数据库
	include '../Db_Connect.php';

	// 连接数据库
	$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
	mysqli_query($conn, "SET NAMES UTF-8");

	// 验证是否存在该账号
	$sql_checkuser = "SELECT * FROM tbk_user WHERE user_name = '$user'";
	$result_checkuser=mysqli_query($conn,$sql_checkuser);
	$row_checkuser=mysqli_num_rows($result_checkuser);
	if ($row_checkuser) {
		// 如果存在，则代表该帐号已经被注册
		$result = array(
			"code" => "112",
			"msg" => "该帐号已被注册"
		);
	}else{
		$user_id = rand(100000,999999);
		$sql_creatuser = "INSERT INTO tbk_user (user_id, user_name, user_pwd, user_email, ulimit, pid, appkey, tbname) VALUES ('$user_id', '$user', '$pwd', '$user_email', '1', '$pid', '$appkey', '$tbname')";
		if ($conn->query($sql_creatuser) === TRUE) {
		    $result = array(
				"code" => "100",
				"msg" => "创建成功"
			);
		} else {
		    $result = array(
				"code" => "113",
				"msg" => "注册失败，数据库配置发生错误，请查看Add-User-do.php是否存在服务错误，可F12打开开发者工具选择NetWork->Preview查看网络请求进行排查。"
			);
		}
	}
}
echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>