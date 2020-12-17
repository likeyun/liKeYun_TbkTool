<?php
header("Content-type:application/json");
session_start();
if(isset($_SESSION["tbktools.admin"])){

	// 引入数据库配置
	include '../Db_Connect.php';

	// 连接数据库
	$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
	mysqli_query($conn, "SET NAMES UTF-8");

	// 获取project_id
	$project_id = trim($_GET["projectid"]);

	if(empty($project_id)){
		$result = array(
			"result" => "101",
			"msg" => "非法请求"
		);
	}else{
		// 获得项目内容
  		$sql_projectMsg = "SELECT * FROM tbk_active_project WHERE active_project_id = '$project_id'";
  		$result_projectMsg = $conn->query($sql_projectMsg);
  		if ($result_projectMsg->num_rows > 0) {
  			while($row_projectMsg = $result_projectMsg->fetch_assoc()) {

  				// 获得项目图片、内容、活动id
  				$active_pic = $row_projectMsg["active_pic"];
  				$active_text = $row_projectMsg["active_text"];
  				$active_id = $row_projectMsg["active_id"];

  				// 根据活动id，获得活动标题
  				$sql_active_title = "SELECT * FROM tbk_active_zjy WHERE active_id = '$active_id'";
		  		$result_active_title = $conn->query($sql_active_title);
		  		if ($result_active_title->num_rows > 0) {
		  			while($row_active_title = $result_active_title->fetch_assoc()) {
		  				$active_title = $row_active_title["active_title"];
		  			}
		  		}else{
		  			// 没有返回结果
		  			$active_title = "该活动不存在";
		  		}

		  		// 返回结果
  				$result = array(
					"result" => "100",
					"msg" => "获取成功",
					"pic" => $active_pic,
					"text" => $active_text,
					"active_title" => $active_title,
					"active_id" => $active_id
				);
  			}
  		}else{
  			$result = array(
				"result" => "103",
				"msg" => "不存在此项目"
			);
  		}
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