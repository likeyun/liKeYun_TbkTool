<?php
header("Content-type:application/json");

// 获得长链接
$long_url = trim($_GET["long_url"]);

// 在这里设置你的appKey
// 在这里设置你的appKey
// 在这里设置你的appKey
// 在这里设置你的appKey
// 在这里设置你的appKey
$appKey = '填写你的快站appKey'; // 访问 【https://kuaima.kuaizhan.com/v1/settings/apis/short-url】 登录后查看你的appKey
// 在这里设置你的appKey
// 在这里设置你的appKey
// 在这里设置你的appKey
// 在这里设置你的appKey
// 在这里设置你的appKey

if (empty($appKey) || !isset($appKey) || $appKey == '填写你的快站appKey') {
    $result = array(
        "result" => "103",
        "msg" => "快站短网址的appKey未设置，请在源码目录：api/kuaizhan.php里面设置你的appKey"
    );
    echo json_encode($result,JSON_UNESCAPED_UNICODE);
    exit;
}

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
    curl_setopt($ch, CURLOPT_URL, 'https://cloud.kuaizhan.com/api/v1/km/genShortLink?appKey='.$appKey.'&link='.$long_url);
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
    if (filter_var($dwzStr, FILTER_VALIDATE_URL) !== false) {
        $result = array(
            "result" => "100",
            "msg" => "生成成功",
            "dwz" => $dwzStr
        );
    }else{
        $result = array(
            "result" => "104",
            "msg" => "生成失败，短网址API可能失效了，可以选择其他API或暂不生成",
            "dwz" => $dwzStr
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