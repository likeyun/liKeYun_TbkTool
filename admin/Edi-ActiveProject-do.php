<?php
header("Content-type:application/json");

// 获得前端POST过来的参数
$project_text = trim($_POST["project_text"]);
$project_pic = trim($_POST["project_pic"]);
$project_id = trim($_POST["project_id"]);
$active_title = trim($_POST["active_title"]);
$active_id = trim($_POST["active_id"]);

// 验证登录状态
session_start();
if(isset($_SESSION["tbktools.admin"])){
	// 过滤空值
	if (empty($project_text)) {
		$result = array(
			"result" => "101",
			"msg" => "项目内容不得为空"
		);
	}else if (empty($project_id)) {
		$result = array(
			"result" => "102",
			"msg" => "非法操作"
		);
	}else{
		// 引入数据库配置
		include '../Db_Connect.php';

		// 连接数据库
		$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
		mysqli_query($conn, "SET NAMES UTF-8");

		// 获取当前项目所属的活动id
		$sql_active_id = "SELECT * FROM tbk_active_project WHERE active_project_id = '$project_id'";
  		$result_active_id = $conn->query($sql_active_id);
  		if ($result_active_id->num_rows > 0) {
  			while($row_active_id = $result_active_id->fetch_assoc()) {
  				$active_id = $row_active_id["active_id"];
  			}
  		}else{
  			// 
  		}

		// 更新项目
		$sql_update_project = "UPDATE tbk_active_project SET active_pic='$project_pic',active_text='$project_text' WHERE active_project_id='$project_id'";
		$sql_update_active_title = "UPDATE tbk_active_zjy SET active_title='$active_title' WHERE active_id='$active_id'";
		
		// 验证更新项目结果
		if ($conn->query($sql_update_project) === TRUE) {

			// 验证更新活动标题结果
		    if ($conn->query($sql_update_active_title) === TRUE) {
			    $result = array(
					"result" => "100",
					"msg" => "更新成功",
					"active_id" => $active_id
				);
			} else {
			    $result = array(
					"result" => "109",
					"msg" => "更新失败"
				);
			}

		} else {

			// 项目更新失败
		    $result = array(
				"result" => "111",
				"msg" => "项目更新失败"
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