<?php
header("Content-type:application/json");
session_start();
if(isset($_SESSION["tbktools.admin"])){

	// 引入数据库配置
	include '../Db_Connect.php';

	// 连接数据库
	$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
    mysqli_query($conn, "SET NAMES UTF-8");

	// 获取$userid
	$userid = trim($_GET["userid"]);

	if(empty($userid)){
		$result = array(
			"result" => "101",
			"msg" => "非法请求"
		);
	}else{
	    // 判断当前登录的用户是否为管理员
	    $loginuser = $_SESSION["tbktools.admin"];
	    $sql_loginuser_limit = "SELECT * FROM tbk_user WHERE user_name = '$loginuser'";
        $result_loginuser_limit = mysqli_query($conn, $sql_loginuser_limit);
        while($row_loginuser_limit = mysqli_fetch_assoc($result_loginuser_limit)) {
            $loginuser_limit = $row_loginuser_limit["ulimit"];
        }
        if($loginuser_limit !== 'admin'){
            $result = array(
		        "code" => "103",
		        "msg" => '没有管理权限'
	        );
        }else{
            // 判断即将删除的用户是否为管理员
    	    $sql_limit = "SELECT * FROM tbk_user WHERE user_id = '$userid'";
            $result_limit = mysqli_query($conn, $sql_limit);
            while($row_limit = mysqli_fetch_assoc($result_limit)) {
                $limit = $row_limit["ulimit"];
                $user_name = $row_limit["user_name"];
            }
            if($limit == 'admin'){
                $result = array(
		            "code" => "104",
		            "msg" => '无法删除管理员账号'
	            );
            }else{
                // 删除账号
                mysqli_query($conn,"DELETE FROM tbk_user WHERE user_id='$userid'");
                // 删除中间页数据
                mysqli_query($conn,"DELETE FROM tbk_zjy WHERE user='$user_name'");
                // 删除活动页数据
                mysqli_query($conn,"DELETE FROM tbk_active_zjy WHERE user='$user_name'");
                $result = array(
		            "code" => "100",
		            "msg" => '已删除'
	            );
            }
        }
        mysqli_close($conn);
	}
}else{
	$result = array(
		"code" => "102",
		"msg" => "未登录"
	);
}
// 返回JSON
echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>