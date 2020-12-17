<?php
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
    $urlencode = urlencode($long_url);
    curl_setopt($ch, CURLOPT_URL, 'http://api.r6f.cn/api.php?url='.$urlencode.'&apikey=17620141291@72110c8cd9aeaeb7dcb6646bcb339cff');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    // 对认证证书来源的检查
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    // 从证书中检查SSL加密算法是否存在
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    //获取的信息以文件流的形式返回，而不是直接输出
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //发起请求
    $dwzStr = curl_exec($ch);

    //关闭请求
    curl_close($ch);

    // 返回结果
    $result = array(
        "result" => "100",
        "msg" => "生成成功",
        "dwz" => $dwzStr
    );
}else{
    $result = array(
        "result" => "102",
        "msg" => "长链接不合法"
    );
}

// 返回JSON
echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>