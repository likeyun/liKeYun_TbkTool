<?php
error_reporting(E_ALL^E_NOTICE^E_WARNING);
header("Content-type:application/json");

//获得配置
$servername = trim($_POST["dbservername"]);
$dbusername = trim($_POST["dbusername"]);
$dbpassword = trim($_POST["dbpassword"]);
$dbname = trim($_POST["dbname"]);
$adminuser = trim($_POST["adminuser"]);
$adminpwd = trim($_POST["adminpwd"]);
$pid = trim($_POST["pid"]);
$tbname = trim($_POST["tbname"]);
$appkey = trim($_POST["appkey"]);
 
// 创建连接
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// 检测连接
if ($conn->connect_error) {
	$errormsg = $conn->connect_error;
	if (strpos($errormsg,'password') !== false) {
		$result = array(
			"result" => "101",
			"msg" => "数据库账号或密码错误"
		);
	}else if (strpos($errormsg,'host') !== false) {
		$result = array(
			"result" => "102",
			"msg" => "数据库服务器地址有误"
		);
	}else{
		$result = array(
			"result" => "103",
			"msg" => "数据库连接失败，原因：".$conn->connect_error
		);
	}
}else{
	// 过滤表单
	if (empty($servername)) {
		$result = array(
			"result" => "102",
			"msg" => "数据库服务器地址还没填"
		);
	}else if (empty($dbusername)) {
		$result = array(
			"result" => "103",
			"msg" => "数据库账号还没填"
		);
	}else if (empty($dbpassword)) {
		$result = array(
			"result" => "104",
			"msg" => "数据库密码还没填"
		);
	}else if (empty($dbname)) {
		$result = array(
			"result" => "105",
			"msg" => "数据库名还没填"
		);
	}else if (empty($adminuser)) {
		$result = array(
			"result" => "106",
			"msg" => "管理员账号还没填"
		);
	}else if (empty($adminpwd)) {
		$result = array(
			"result" => "107",
			"msg" => "管理员密码还没填"
		);
	}else if (empty($pid)) {
		$result = array(
			"result" => "108",
			"msg" => "PID还没填"
		);
	}else if (empty($tbname)) {
		$result = array(
			"result" => "109",
			"msg" => "淘宝账号还没填"
		);
	}else if (empty($appkey)) {
		$result = array(
			"result" => "110",
			"msg" => "AppKey还没填"
		);
	}else{
		$db_file = "../Db_Connect.php";
		if(file_exists($db_file)){
			$result = array(
				"result" => "111",
				"msg" => "请勿重新安装！"
			);
		}else{

			// 创建tbk_zjy数据表
			$tbk_zjy = "CREATE TABLE tbk_zjy (
				id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				zjy_id VARCHAR(32) NULL,
				zjy_long_title TEXT(1000) NULL,
				zjy_short_title VARCHAR(32) NULL,
				zjy_yprice VARCHAR(32) NULL,
				zjy_qhprice VARCHAR(32) NULL,
				zjy_cover TEXT(1000) NULL,
				zjy_yuming TEXT(1000) NULL,
				zjy_template VARCHAR(32) NULL,
				zjy_dwz VARCHAR(32) NULL,
				zjy_pv VARCHAR(32) DEFAULT '0',
				zjy_creat_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				zjy_tkl VARCHAR(32) NULL
			)";

			// 创建tbk_yuming数据表
			$tbk_yuming = "CREATE TABLE tbk_yuming (
				id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				yuming TEXT(1000) NULL,
				creat_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
			)";

			// 创建tbk_user数据表
			$tbk_user = "CREATE TABLE tbk_user (
				id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				user_id VARCHAR(32) NULL,
				user_name VARCHAR(32) NULL,
				user_pwd VARCHAR(32) NULL,
				user_creatdate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				user_email TEXT(1000) NULL
			)";

			// 创建tbk_set数据表
			$tbk_set = "CREATE TABLE tbk_set (
				Set_Obj VARCHAR(32) PRIMARY KEY,
				Set_Val TEXT(1000) NULL
			)";

			// 创建tbk_dwzapi数据表
			$tbk_dwzapi = "CREATE TABLE tbk_dwzapi (
				id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				dwztitle VARCHAR(32) NULL,
				dwzapi TEXT(1000) NULL
			)";

			// 创建tbk_gzh_zjy数据表
			$tbk_gzh_zjy = "CREATE TABLE tbk_gzh_zjy (
				id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				zid VARCHAR(32) NULL,
				title TEXT(1000) NULL,
				openid VARCHAR(32) NULL,
				tkl VARCHAR(32) NULL,
				gzhzjy_pv VARCHAR(32) DEFAULT '0',
				creat_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				dwz VARCHAR(32) NULL
			)";
			$conn->query($tbk_gzh_zjy);

			// 创建tbk_gzh_user数据表
			$tbk_gzh_user = "CREATE TABLE tbk_gzh_user (
				id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				openid VARCHAR(32) NULL,
				reg_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
			)";
			$conn->query($tbk_gzh_user);

			// 创建tbk_gzh_set数据表
			$tbk_gzh_set = "CREATE TABLE tbk_gzh_set (
				gzh_set_obj VARCHAR(32) PRIMARY KEY,
				gzh_set_val VARCHAR(32) NULL
			)";
			$conn->query($tbk_gzh_set);

			// 创建tbk_active_zjy数据表
			$tbk_active_zjy = "CREATE TABLE tbk_active_zjy (
				id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				active_id VARCHAR(32) NULL,
				active_title VARCHAR(32) NULL,
				active_creat_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				active_pv VARCHAR(32) DEFAULT '0',
				active_dwz VARCHAR(32) NULL,
				active_yuming TEXT(100) NULL
			)";
			$conn->query($tbk_active_zjy);

			// 创建tbk_active_project数据表
			$tbk_active_project = "CREATE TABLE tbk_active_project (
				id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				active_id VARCHAR(32) NULL,
				active_project_id VARCHAR(32) NULL,
				active_pic TEXT(100) NULL,
				active_text TEXT(100) NULL,
				active_copy VARCHAR(32) NULL
			)";
			$conn->query($tbk_active_project);


			if ($conn->query($tbk_zjy) === TRUE) {

				if ($conn->query($tbk_yuming) === TRUE) {

					if ($conn->query($tbk_user) === TRUE) {

						// 创建管理员账号
						$user_id = rand(100000,999999);
						mysqli_query($conn,"INSERT INTO tbk_user (user_id, user_name, user_pwd, user_email) VALUES ('$user_id', '$adminuser', '$adminpwd', 'admin@qq.com')");

						if ($conn->query($tbk_set) === TRUE) {

							// 创建初始化设置
							mysqli_query($conn,"INSERT INTO tbk_set (Set_Obj, Set_Val) VALUES ('zjy_pid', '$pid')");
							mysqli_query($conn,"INSERT INTO tbk_set (Set_Obj, Set_Val) VALUES ('zjy_tbname', '$tbname')");
							mysqli_query($conn,"INSERT INTO tbk_set (Set_Obj, Set_Val) VALUES ('zjy_appkey', '$appkey')");
							mysqli_query($conn,"INSERT INTO tbk_gzh_set (gzh_set_obj, gzh_set_val) VALUES ('Token', '未设置')");
							mysqli_query($conn,"INSERT INTO tbk_gzh_set (gzh_set_obj, gzh_set_val) VALUES ('注册验证码', '未设置')");

							if ($conn->query($tbk_dwzapi) === TRUE) {

								// 创建默认短网址API
								$dwzapiurl = "http://".$_SERVER['HTTP_HOST'].dirname(dirname($_SERVER["REQUEST_URI"]))."/api/dwz.php?long_url=";
								mysqli_query($conn,"INSERT INTO tbk_dwzapi (dwztitle, dwzapi) VALUES ('R6F短网址', '$dwzapiurl')");

								//数据库配置文件
								$mysql_data = '<?php
								$db_url = "'.$servername.'";
								$db_user = "'.$dbusername.'";
								$db_pwd = "'.$dbpassword.'";
								$db_name = "'.$dbname.'";
								?>';
								//生成数据库配置文件
								file_put_contents('../Db_Connect.php', $mysql_data);
								$result = array(
									"result" => "100",
									"msg" => "创建成功"
								);

							}else{
								$result = array(
									"result" => "116",
									"msg" => "创建tbk_set失败，原因：".$conn->error
								);
							}
						}else{
							$result = array(
								"result" => "115",
								"msg" => "创建tbk_set失败，原因：".$conn->error
							);
						}
					}else{
						$result = array(
							"result" => "114",
							"msg" => "创建tbk_user失败，原因：".$conn->error
						);
					}
				}else{
					$result = array(
						"result" => "113",
						"msg" => "创建tbk_yuming失败，原因：".$conn->error
					);
				}
			}else{
				$result = array(
					"result" => "112",
					"msg" => "创建tbk_zjy失败，原因：".$conn->error
				);
			}
		}
	}
}
echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>