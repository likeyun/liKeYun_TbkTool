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
		// 获得长链接和短链接
  		$sql_zjyMsg = "SELECT * FROM tbk_active_zjy WHERE active_id = '$active_id'";
  		$result_zjyMsg = $conn->query($sql_zjyMsg);
  		if ($result_zjyMsg->num_rows > 0) {
  			while($row_zjyMsg = $result_zjyMsg->fetch_assoc()) {

  				// 获取中间页短网址和落地页域名
  				$active_dwz = $row_zjyMsg["active_dwz"];
  				$active_yuming = $row_zjyMsg["active_yuming"];

  				// 拼接长链接
  		        $longUrl_ = substr($active_yuming.dirname(dirname($_SERVER["REQUEST_URI"]))."/",-2);
  		        if($longUrl_ == '//'){
  		            $longUrl = $active_yuming.dirname(dirname($_SERVER["REQUEST_URI"]))."activezjy.php?activeid=".$active_id;
  		        }else{
  		            $longUrl = $active_yuming.dirname(dirname($_SERVER["REQUEST_URI"]))."/"."activezjy.php?activeid=".$active_id;
  		        }
  				$result = array(
					"result" => "100",
					"msg" => "分享成功",
					"dwz" => $active_dwz,
					"longUrl" => $longUrl
				);
  			}
  		}else{
  			$result = array(
				"result" => "103",
				"msg" => "非法请求"
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