<?php

// 定义TOKEN
define("TOKEN", $_GET["t"]);

//用于回复用户消息
function responseMsg(){

	// 引入数据库配置
	require_once '../Db_Connect.php';
	 
	// 创建连接
	$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
	// 检查连接
	if ($conn->connect_error) {
	    die("连接失败: " . $conn->connect_error);
	}

    $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
    if (!empty($postStr)){
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $MsgT = $postObj->MsgType;
        $time = time();

        // 验证是否为注册用户
		$sql_check_isreg_openid = "SELECT * FROM tbk_gzh_user WHERE openid = '$fromUsername'";
		$result_check_isreg_openid=mysqli_query($conn,$sql_check_isreg_openid);
		$row_isreg_openid=mysqli_num_rows($result_check_isreg_openid);

        //如果用户发的text类型
        if($MsgT=="text"){
            $key = trim($postObj->Content); // 发送的文本关键词
            $fromUsername = $postObj->FromUserName;
            $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        </xml>"; 
            $msgType = "text";

            // 验证关键词是否为淘宝联盟的淘口令文案
            if(strpos($key,'tb') !== false){

            	// 验证是否为注册用户
				if ($row_isreg_openid) {
					// 验证通过
					// 提取商品标题
	                $title = substr($key,strripos($key,"【"));
	                // 提取淘口令
					$preg_tkl= '/￥[\s\S]*?￥/i';
					preg_match_all($preg_tkl,$key,$res_tkl);
					$tkl_str = $res_tkl[0][0];
	                $taokouling = "1".$tkl_str.":/复制这段文字，打开手机淘宝即可查看商品详情".$title;
					// 生成zid
					$zid = rand(10000,99999);
					// 插入数据
					$sql = "INSERT INTO tbk_gzh_zjy (title, zid, tkl, openid) VALUES ('$title','$zid','$taokouling','$fromUsername')";
					// 检查插入结果
					if ($conn->query($sql) === TRUE) {
					    // 插入成功
					    // 创建短网址
					    $long_url = "http://".$_SERVER['HTTP_HOST'].dirname(dirname($_SERVER["REQUEST_URI"]))."/gzhzjy.php?zid=".$zid; // 拼接长链接
					    //初始化 CURL
						$ch = curl_init();
						// 请求地址
						// 这是经过urlencode转码的中间页长链接
						// 配置短网址api的时候，请以get方式传入中间页长链接
						// 例如你的短网址api链接是http://www.xxx.com/dwz.php?url=
						// 那么下面的$dwzapi应该是：
						// $dwzapi = "http://www.xxx.com/dwz.php?url=".$urlencode;
						// 你的api请求后最终返回的一定要是text格式的短网址
						$urlencode = urlencode($long_url);
						// 短网址api
					    $dwzapi = "http://api.r6f.cn/api.php?url=".$urlencode."&apikey=17620141291@72110c8cd9aeaeb7dcb6646bcb339cff";
						curl_setopt($ch, CURLOPT_URL, $dwzapi);
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
						// 把短网址更新到数据库
						mysqli_query($conn,"UPDATE tbk_gzh_zjy SET dwz='$dwzStr' WHERE zid='$zid'");
						// 断开数据库连接
						$conn->close();
						// 发送结果
					    $contentStr = "【创建成功】\n短网址：".$dwzStr."\n"."长网址：".$long_url."\n\n"."如需删除，请回复[删除".$zid."] ";
					} else {
					    // 创建失败
					    $contentStr = "创建中间页失败，系统发生错误";
					}
				}else{
					// 验证不通过
					$contentStr = "您未注册，没有创建中间页的权限！";
				}
            }else if(strpos($key,'删除') !== false){

            	// 验证是否为注册用户
            	if ($row_isreg_openid) {
            		// 获取删除的zid
	            	$delzid = substr($key,strripos($key,"删除")+6);
	            	// 验证你要删除的zid是不是你创建的
	            	$check_youcreat = "SELECT * FROM tbk_gzh_zjy WHERE zid = '$delzid'";
	            	$result_youcreat = $conn->query($check_youcreat);
	            	if ($result_youcreat->num_rows > 0) {
	            		while($row_youcreat = $result_youcreat->fetch_assoc()) {
	            			$openid_youcreat = $row_youcreat["openid"];
	            			if ($fromUsername == $openid_youcreat) {
	            				// openid一致，代表你有删除的权限
	            				mysqli_query($conn,"DELETE FROM tbk_gzh_zjy WHERE zid='$delzid'");
				            	$contentStr = "已删除zid为".$delzid."的中间页";
				            	mysqli_close($conn);
	            			}else{
	            				// openid不一致，你不能删除
	            				$contentStr = "你不能删除别人的中间页！";
	            			}
	            		}
	            	}else{
	            		// 查询无果，参数错误
	            		$contentStr = "删除失败，此中间页不存在";
	            	}

            	}else{
            		$contentStr = "你不是注册用户，没有删除权限！";
            	}

            }else if (strpos($key,'注册') !== false) {
            	// 注册过的openid才可以创建中间页
            	// 获得关键词传过来的登录验证码
            	$key_yzm = substr($key,strripos($key,"注册")+6);
            	// 获取验证码
            	// 获取注册验证码
				$getset = "SELECT * FROM tbk_gzh_set WHERE gzh_set_obj = '注册验证码'";
				$result_set = $conn->query($getset);
				if ($result_set->num_rows > 0){
					while($row_set = $result_set->fetch_assoc()){
						$reg_yzm = $row_set["gzh_set_val"];
					}
				}else{
					// 
				}
            	// 判断注册验证码的正确性
            	if ($key_yzm == $reg_yzm) {
            		// 验证是否已经注册
					$sql_check_openid = "SELECT * FROM tbk_gzh_user WHERE openid = '$fromUsername'";
					$result_check_openid=mysqli_query($conn,$sql_check_openid);
					$row_openid=mysqli_num_rows($result_check_openid);
					if ($row_openid) {
						$contentStr = "您已经注册过了，无需再次注册！";
					}else{

						// 注册用户
	            		$sql_reg_openid = "INSERT INTO tbk_gzh_user (openid) VALUES ('$fromUsername')";

	            		// 验证注册结果
	            		if ($conn->query($sql_reg_openid) === TRUE) {
	            			$contentStr = "注册成功！您可以到淘宝联盟复制淘口令文案发给我，即可快速创建中间页。";
	            		}else{
	            			$contentStr = "注册失败，数据库发生错误";
	            		}
					}
            	}else{
            		$contentStr = "注册验证码错误";
            	}
            }else{
                // 不是淘宝联盟文案
                $contentStr = "您发的关键词不在我们的指令库";
            }

            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            echo $resultStr;
            exit;
        }

        //如果用户发的event（事件）类型
        if($MsgT=="event"){
            $Event = $postObj->Event;
            // 当用户关注的时候调用的事件
            if ($Event==subscribe) {
                $contentStr = "欢迎使用淘宝客中间页公众号版v1.1.0";
            }else if ($Event==unsubscribe) {
                // 当用户取消关注时调用的事件
            }
            $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        </xml>"; 
            $Title = $postObj->Title;
            $Description = $postObj->Description;
            $Url = $postObj->Url;
            $msgType = 'text';
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            echo $resultStr;
            exit;
        }
    }else{
            echo "";
            exit;
    }
}

    $echoStr = $_GET["echostr"];
    //如果有$echoStr说明是对接
    if (!empty($echoStr)) {
        //对接规则
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ){
            echo $echoStr;
        }else{
            echo "";
            exit;
        }
    }else{
        responseMsg();
    }
?>