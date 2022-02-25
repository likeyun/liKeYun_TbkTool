<?php
error_reporting(E_ALL^E_NOTICE^E_WARNING);
header("Content-type:application/json");

// 获得前端POST过来的参数（淘口令）
$autozjy_tkl = $_POST["autozjy_tkl"];

// 过滤空值
if (empty($autozjy_tkl)) {
	$result = array(
		"result" => "101",
		"msg" => "淘口令不得为空"
	);
}else{
	// 引入数据库配置
	include '../Db_Connect.php';

	// 连接数据库
	$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
	mysqli_query($conn, "SET NAMES UTF-8");

	// 验证PID
	$pid_check = "SELECT * FROM tbk_set WHERE Set_Obj = 'zjy_pid'";
	$result_check_pid = $conn->query($pid_check);

	if ($result_check_pid->num_rows > 0) {
		while($row_check_pid = $result_check_pid->fetch_assoc()) {
			$pid = $row_check_pid["Set_Val"];
		}
		if (empty($pid)) {
			$result = array(
				"result" => "102",
				"msg" => "未设置PID"
			);
		}else{
			// 验证淘宝账户
			$tbname_check = "SELECT * FROM tbk_set WHERE Set_Obj = 'zjy_tbname'";
			$result_check_tbname = $conn->query($tbname_check);

			if ($result_check_tbname->num_rows > 0) {
				while($row_check_tbname = $result_check_tbname->fetch_assoc()) {
					$tbname = $row_check_tbname["Set_Val"];
				}
				if (empty($tbname)) {
					$result = array(
						"result" => "103",
						"msg" => "未设置淘宝账号"
					);
				}else{
					// 验证AppKey
					$appkey_check = "SELECT * FROM tbk_set WHERE Set_Obj = 'zjy_appkey'";
					$result_check_appkey = $conn->query($appkey_check);

					if ($result_check_appkey->num_rows > 0) {
						while($row_check_appkey = $result_check_appkey->fetch_assoc()) {
							$appkey = $row_check_appkey["Set_Val"];
						}
						if (empty($appkey)) {
							$result = array(
								"result" => "104",
								"msg" => "未设置appkey"
							);
						}else{
							// 验证结束，开始解析
							// ①淘口令和淘宝账号需要进行url编码
							$tkl_urlencode = urlencode($autozjy_tkl);
							$tbname_urlencode = urlencode($tbname);
							
							// ②拼接HTTP请求URL并发起Get请求
							$Read_Tkl_Url = file_get_contents("http://api.web.21ds.cn/taoke/doItemHighCommissionPromotionLinkByAll?apkey=".$appkey."&tbname=".$tbname_urlencode."&pid=".$pid."&content=".$tkl_urlencode."&tpwd=1&hasiteminfo=1");
							
							// ③解析返回的JSON
							$Read_Tkl_Url_Arr = json_decode($Read_Tkl_Url, true);
							$code = $Read_Tkl_Url_Arr["code"]; // 返回码
							$msg = $Read_Tkl_Url_Arr["msg"]; // 返回信息

							// 解析结果变量
							$long_title = $Read_Tkl_Url_Arr["data"]["item_info"]["title"];// 长标题
							$short_title = mb_substr($long_title,0,13,'utf-8');// 短标题
							$picUrl = $Read_Tkl_Url_Arr["data"]["item_info"]["pict_url"];// 主图地址
							$yprice = $Read_Tkl_Url_Arr["data"]["item_info"]["zk_final_price"];// 原价
							$youhuiquan = $Read_Tkl_Url_Arr["data"]["youhuiquan"]; // 优惠券价格
							$qhprice = $yprice-$youhuiquan; // 券后价
							$mytkl = $Read_Tkl_Url_Arr["data"]["tpwd"]; // 淘口令

							// 原价格式化
							if(strpos($yprice,'.') !==false){
								// 如果包含小数点，就要在最后面加一个0
								$yuanjia = $yprice."0";
							}else{
								// 不包含小数点，就要在最后面加.00
								$yuanjia = $yprice.".00";
							}

							// 券后价格式化
							if(strpos($qhprice,'.') !==false){
								// 如果包含小数点，就要在最后面加一个0
								$quanhoujia = $qhprice."0";
							}else{
								// 不包含小数点，就要在最后面加.00
								$quanhoujia = $qhprice.".00";
							}

							// 判断解析结果
							if ($code == 200) {
								// 如果返回码为200就代表解析成功
								$result = array(
									"result" => "100",
									"msg" => "淘口令解析成功",
									"goods_msg" => array(
										"zjy_long_title" => $long_title,
										"zjy_short_title" => $short_title,
										"zjy_yprice" => $yuanjia,
										"zjy_qhprice" => $quanhoujia,
										"zjy_tkl" => $mytkl,
										"zjy_cover" => $picUrl
									)
								);
							}else{
								// 否则解析不成功，返回不成功的原因
								if ($code == '-1') {
									$result = array(
										"result" => $code,
										"msg" => "服务器ip地址未加入白名单，请添加IP地址：".$msg
									);
								}else{
									$result = array(
										"result" => $code,
										"msg" => $msg
									);
								}
							}
						}
					}
				}
			}
		}
	}
}
echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>