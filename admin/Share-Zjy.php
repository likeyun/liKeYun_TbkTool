<?php
header("Content-type:application/json");
session_start();
if(isset($_SESSION["tbktools.admin"])){

	// 引入数据库配置
	include '../Db_Connect.php';

	// 连接数据库
	$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
	mysqli_query($conn, "SET NAMES UTF-8");

	// 获取zid
	$zid = trim($_GET["zid"]);

	if(empty($zid)){
		$result = array(
			"result" => "101",
			"msg" => "非法请求"
		);
	}else{
		// 获得长链接和短链接
  		$sql_zjyMsg = "SELECT * FROM tbk_zjy WHERE zjy_id = '$zid'";
  		$result_zjyMsg = $conn->query($sql_zjyMsg);
  		if ($result_zjyMsg->num_rows > 0) {
  			while($row_zjyMsg = $result_zjyMsg->fetch_assoc()) {
  				$dwz = $row_zjyMsg["zjy_dwz"];
  				$zjy_yuming = $row_zjyMsg["zjy_yuming"];
  				
  				// 拼接长链接
  				$longUrl_ = substr($zjy_yuming.dirname(dirname($_SERVER["REQUEST_URI"]))."/",-2);
  				if($longUrl_ == '//'){
  		            $longUrl = $zjy_yuming.dirname(dirname($_SERVER["REQUEST_URI"]))."index.php?zid=".$zid;
  		        }else{
  		            $longUrl = $zjy_yuming.dirname(dirname($_SERVER["REQUEST_URI"]))."/"."index.php?zid=".$zid;
  		        }
  				$result = array(
					"result" => "100",
					"msg" => "分享成功",
					"dwz" => $dwz,
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