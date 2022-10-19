<?php

header("Content-type:application/json");

// 获得前端POST过来的参数（淘口令）
$autozjy_tkl = trim($_POST["autozjy_tkl"]);

// 获取当前登录用户
session_start();
$user = $_SESSION["tbktools.admin"];

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

	// 验证PID（在用户表获取当前登录的用户的pid）
	$pid_check = "SELECT * FROM tbk_user WHERE user_name = '$user'";
	$result_check_pid = $conn->query($pid_check);

	if ($result_check_pid->num_rows > 0) {
		while($row_check_pid = $result_check_pid->fetch_assoc()) {
			$pid = $row_check_pid["pid"];
		}
		if (empty($pid)) {
		    
			$result = array(
				"result" => "102",
				"msg" => "未设置PID"
			);
		}else{
			// 验证淘宝账户（在用户表获取当前登录的用户的tbname）
			$tbname_check = "SELECT * FROM tbk_user WHERE user_name = '$user'";
			$result_check_tbname = $conn->query($tbname_check);

			if ($result_check_tbname->num_rows > 0) {
				while($row_check_tbname = $result_check_tbname->fetch_assoc()) {
					$tbname = $row_check_tbname["tbname"];
				}
				if (empty($tbname)) {
				    
					$result = array(
						"result" => "103",
						"msg" => "未设置淘宝账号"
					);
				}else{
					// 验证AppKey（在用户表获取当前登录的用户的appkey）
					$appkey_check = "SELECT * FROM tbk_user WHERE user_name = '$user'";
					$result_check_appkey = $conn->query($appkey_check);

					if ($result_check_appkey->num_rows > 0) {
						while($row_check_appkey = $result_check_appkey->fetch_assoc()) {
							$appkey = $row_check_appkey["appkey"];
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
				            // $tbname_urlencode = urlencode($tbname);
							
							// ②拼接HTTP请求URL并发起Get请求
				// 			$Read_Tkl_Url = file_get_contents("http://api.web.ecapi.cn/taoke/doItemHighCommissionPromotionLinkByAll?apkey=".$appkey."&tbname=".$tbname_urlencode."&pid=".$pid."&content=".$tkl_urlencode."&tpwd=1&hasiteminfo=1");
							
							$Read_Tkl_Url = file_get_contents("https://api.zhetaoke.com:10001/api/open_gaoyongzhuanlian_tkl.ashx?appkey=".$appkey."&sid=".$tbname."&pid=".$pid."&tkl=".$tkl_urlencode."&signurl=5");
							
							// ③解析返回的JSON
				// 			$Read_Tkl_Url_Arr = json_decode($Read_Tkl_Url);
				// 			$code = $Read_Tkl_Url_Arr["status"]; // 返回码
				// 			$msg = $Read_Tkl_Url_Arr["msg"]; // 返回信息
				            
				            $code = json_decode($Read_Tkl_Url,true)["status"]; // 状态码
				            
				            // 解析结果
				            $ReadJson = json_decode($Read_Tkl_Url,true)["content"][0];
				            $short_title = $ReadJson['title']; // 短标题
				            $yprice = $ReadJson['size']; // 原价
				            $qhprice = $ReadJson['quanhou_jiage']; // 券后价
				            $youhuiquan = $yprice-$qhprice; // 优惠券价格
				            $long_title = $ReadJson['tao_title'];
				            $picUrl = $ReadJson['small_images']; // 主图地址
				            $mytkl = $ReadJson['tkl'];  // 淘口令
				            $AppRedUrl = $ReadJson['shorturl2']; // 微信跳转淘宝APP的链接
				            
							// 解析结果变量
				// 			$long_title = $Read_Tkl_Url_Arr["data"]["item_info"]["title"];// 长标题
				// 			$short_title = mb_substr($long_title,0,13,'utf-8');// 短标题
				// 			$picUrl = $Read_Tkl_Url_Arr["data"]["item_info"]["pict_url"];// 主图地址
				// 			$yprice = $Read_Tkl_Url_Arr["data"]["item_info"]["zk_final_price"];// 原价
				// 			$youhuiquan = $Read_Tkl_Url_Arr["data"]["youhuiquan"]; // 优惠券价格
				// 			$qhprice = $yprice-$youhuiquan; // 券后价
				// 			$mytkl = $Read_Tkl_Url_Arr["data"]["tpwd"]; // 淘口令
							
							// 提取微信跳转淘宝APP的链接
                            // $AppRedUrl_1 = substr($Read_Tkl_Url_Arr["data"]["tpwd_str"], strripos($Read_Tkl_Url_Arr["data"]["tpwd_str"], "m.tb.cn/") + 8);
                            
    //                         $AppRedUrl = 'https://s.tb.cn/'.substr($AppRedUrl_1, 0, strrpos($AppRedUrl_1, "  "));

				// 			// 原价格式化
				// 			if(strpos($yprice,'.') !==false){
				// 				// 如果包含小数点，就要在最后面加一个0
				// 				$yuanjia = $yprice."0";
				// 			}else{
				// 				// 不包含小数点，就要在最后面加.00
				// 				$yuanjia = $yprice.".00";
				// 			}

				// 			// 券后价格式化
				// 			if(strpos($qhprice,'.') !==false){
				// 				// 如果包含小数点，就要在最后面加一个0
				// 				$quanhoujia = $qhprice."0";
				// 			}else{
				// 				// 不包含小数点，就要在最后面加.00
				// 				$quanhoujia = $qhprice.".00";
				// 			}

				// 			判断解析结果
							if ($code == 200) {
								// 如果返回码为200就代表解析成功
								$result = array(
									"result" => "100",
									"msg" => "淘口令解析成功",
									"goods_msg" => array(
										"zjy_long_title" => $long_title,
										"zjy_short_title" => $short_title,
										"zjy_yprice" => $yprice,
										"zjy_qhprice" => $qhprice,
										"zjy_tkl" => "19".$mytkl."/:/",
										"zjy_cover" => $picUrl,
										"AppRedUrl" => $AppRedUrl
									)
								);
							}else{
								// 否则解析不成功
								$result = array(
									"result" => $code,
									"msg" => "请检查接口可用性，接口来源：http://www.zhetaoke.com/user/open/open_gaoyongzhuanlian_tkl.aspx"
								);
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