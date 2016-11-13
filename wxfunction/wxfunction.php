<?php

//传入返回的code值，返回用户的信息$arr
function OAuth($code){
	if ($code!='') {
		 $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".APPID."&secret=".SECRET."&code={$_GET['code']}&grant_type=authorization_code";
		 $ch=curl_init($url);
			curl_setopt($ch,CURLOPT_HEADER,0);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			$json=curl_exec($ch);
			curl_close($ch);
			$arr=json_decode($json,ture);
			// {
			//    "access_token":"ACCESS_TOKEN",
			//    "expires_in":7200,
			//    "refresh_token":"REFRESH_TOKEN",
			//    "openid":"OPENID",
			//    "scope":"SCOPE",
			//    "unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"
			// }

			$url="https://api.weixin.qq.com/sns/userinfo?access_token={$arr['access_token']}&openid={$arr['openid']}&lang=zh_CN";
			$ch=curl_init($url);
			curl_setopt($ch,CURLOPT_HEADER,0);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			$json=curl_exec($ch);
			$arr=json_decode($json,ture);
			// {
			//    "openid":" OPENID",
			//    " nickname": NICKNAME,
			//    "sex":"1",
			//    "province":"PROVINCE"
			//    "city":"CITY",
			//    "country":"COUNTRY",
			//     "headimgurl":    "http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/46", 
			// 	"privilege":[
			// 	"PRIVILEGE1"
			// 	"PRIVILEGE2"
			//     ],
			//     "unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"
			// }
			return $arr;
	}else{
		echo "code为空！";
		return -1;
	}
}