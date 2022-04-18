<?php
header("Content-type:application/json");
session_start();
if(isset($_SESSION["tbktools.admin"])){

	// 引入数据库配置
	include '../Db_Connect.php';

	// 连接数据库
	$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
	mysqli_query($conn, "SET NAMES UTF-8");

	// 获取active_id
	$active_id = trim($_GET["activeid"]);

	if(empty($active_id)){
		$result = array(
			"result" => "101",
			"msg" => "非法请求"
		);
	}else{
		// 获得项目列表
  		$sql_project_list = "SELECT * FROM tbk_active_project WHERE active_id = '$active_id' ORDER BY ID ASC";
  		$result_project_list = $conn->query($sql_project_list);
  		if ($result_project_list->num_rows > 0) {
  			$results = array();
  			while($row_project_list = $result_project_list->fetch_assoc()) {

  				// 遍历结果
  				$active_text = $row_project_list["active_text"];
  				$active_project_id = $row_project_list["active_project_id"];

  				// 返回结果
  				$results[] = $row_project_list;
  			}
  		}else{
  			$results = array(
				"result" => "103",
				"msg" => "暂无项目，请添加项目"
			);
  		}
	}
}else{
	$results = array(
		"result" => "102",
		"msg" => "未登录"
	);
}
// 返回JSON
echo json_encode($results,JSON_UNESCAPED_UNICODE);
?>