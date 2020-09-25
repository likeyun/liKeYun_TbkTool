<?php
// 开源作者：TANKING
// 如有遇到安装问题，请加入微信群
// 微信群进群地址：http://pic.iask.cn/fimg/591377922798.jpg
header("Content-type:application/json");

// 获得长链接
$long_url = trim($_GET["long_url"]);

// 过滤
if (empty($long_url)) {
	$result = array(
		"result" => "101",
		"msg" => "请传入长链接"
	);
} else if (strpos($long_url,'http') !== false){
	//初始化 CURL
	$ch = curl_init();
	//请求地址 
	curl_setopt($ch, CURLOPT_URL, 'https://v1.alapi.cn/api/url?url='.urlencode($long_url).'&type=1');
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	// 对认证证书来源的检查
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	// 从证书中检查SSL加密算法是否存在
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	//获取的信息以文件流的形式返回，而不是直接输出
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	//发起请求
	$dwzStr = curl_exec($ch);
	//解析数据
    $arr_dwzStr = json_decode($dwzStr, true);
    $dwz = $arr_dwzStr["data"]["short_url"];
    $code = $arr_dwzStr["code"];
	//关闭请求
	curl_close($ch);

	// 返回结果
	if ($code == 200) {
		$result = array(
			"result" => "100",
			"msg" => "生成成功",
			"dwz" => $dwz
		);
	}else{
		$result = array(
			"result" => "103",
			"msg" => "生成失败",
			"dwz" => $dwz
		);
	}
}else{
	$result = array(
		"result" => "102",
		"msg" => "长链接不合法"
	);
}

// 返回JSON
echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>