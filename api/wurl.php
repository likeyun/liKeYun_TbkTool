<?php
// 开源作者：TANKING
// 如有遇到安装问题，请加入微信群
// 微信群进群地址：http://pic.iask.cn/fimg/591377922798.jpg
header("Content-type:application/json");

// 声明APPID、APPSECRET
$appid = "xxx";
$appsecret = "xxx";

// 获取access_token和jsapi_ticket
function getToken(){
    $file = file_get_contents("access_token.json",true);//读取access_token.json里面的数据
    $result = json_decode($file,true);

//判断access_token是否在有效期内，如果在有效期则获取缓存的access_token
//如果过期了则请求接口生成新的access_token并且缓存access_token.json
if (time() > $result['expires']){
        $data = array();
        $data['access_token'] = getNewToken();
        $data['expires'] = time()+7000;
        $jsonStr =  json_encode($data);
        $fp = fopen("access_token.json", "w");
        fwrite($fp, $jsonStr);
        fclose($fp);
        return $data['access_token'];
    }else{
        return $result['access_token'];
    }
}
 
//获取新的access_token
function getNewToken($appid,$appsecret){
    global $appid;
    global $appsecret;
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret."";
    $access_token_Arr =  file_get_contents($url);
    $token_jsonarr = json_decode($access_token_Arr, true);
    return $token_jsonarr["access_token"];
}

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
    curl_setopt($ch, CURLOPT_URL, 'https://api.weixin.qq.com/cgi-bin/shorturl?access_token='.getToken());
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    $postdata =  '{"action":"long2short","long_url":"'.$long_url.'"}'; 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
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
    $dwz = $arr_dwzStr["short_url"];
    //关闭请求
    curl_close($ch);

    // 返回结果
    $result = array(
        "result" => "100",
        "msg" => "生成成功",
        "dwz" => $dwz
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