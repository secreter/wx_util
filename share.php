<?php
//测试号
define(APPID, "wx129325db79888ee1");
define(SECRET, "9e21fbec9c5fe8926a4f3ce0c4634529");
include_once "wxfunction/wxfunction.php";
require_once "jssdk.php";
$jssdk = new JSSDK(APPID, SECRET);
$signPackage = $jssdk->GetSignPackage();
// $arr=Array ( 
//  'openid' => 'o6UDkwIppYchG79HknNe-9fuYugQ',
//  'nickname' => '✘' ,
//  'sex' => '1', 
//  'language' => 'zh_CN' ,
//  'city' => '',
//  'province' => '',
//  'country' => '中国' ,
//  'headimgurl' => 'http://wx.qlogo.cn/mmopen/16Q9aDojjfkMRfo7TSc9F3SEIiaLaLppczYn5sZ3ceZZUcxGryMH8G5M8bpYfdaQ0JlwNx2yaw9OLNrrrxqAebUFLN2voWlBib/0 ',
//  'privilege' => Array ( ) );
// print_r($arr);
?>
<!DOCTYPE html>
<html>
<head>
	<title>	😁<?php echo $arr['nickname']; ?>挑战成功！</title>
	<meta name="viewport" content="width=device-width,initial-scale=1, maximum-scale=1">
	<meta charset="utf-8">
	<style type="text/css">
		*{
			margin: 0;
			padding: 0;

		}
		html,body{
			height: 100%;
			width: 100%;
		}
		body{
			background: url('http://og7lnhyuz.bkt.clouddn.com/item/image/jpg/bg2.jpg') no-repeat; 
			background-size: cover;
			box-sizing: border-box;
			padding-top: 35%;
		}
		.main{
			background-color: rgba(255,255,255,0.7);
			width: 92%;
			text-align: center;
			padding:50px 10px;
			box-sizing: border-box;
			margin: 0 auto 0 auto;
			border-radius: 2px;
			box-shadow: 0 0 1px 1px rgba(0,0,0,0.3);
		}
		.emoji{	
			font-size: 40px;
		}
		.caidan{
			font-size: 13px;
			text-decoration: underline;
			margin-top: 50px;
		}
		.subcribe{
			position: absolute;
			bottom: 10px;
			font-size: 13px;
			display: block;
			margin: 0 auto;
			width: 100%;
			text-align: center;
		}
	</style>
</head>
<body>
<div class="main">
	<div class="emoji">🙊🙊🙊</div>
	<div class="info"><?php echo $arr['nickname']; ?>你好厉害!你已经接受了挑战了说~壮士好勇猛~</div>
	<div class="caidan" onclick="alert('<?php echo $arr['nickname']; ?>是资深单身狗！鉴定完毕，汪汪汪~🐶🐶🐶')">双十一彩蛋，单身狗慎点</div>
</div>
<div class="hide">
	
</div>
<a class="subcribe" href="http://mp.weixin.qq.com/s?__biz=MzIzNjE4NDI5Nw==&mid=2650650183&idx=1&sn=d23fdadc0380cb40399c525479e56cb8#rd">@南开一梦</a>
</body>
<script>
	function setCookie(c_name,value,time)
	{
		document.cookie=c_name+ "=" +encodeURIComponent(value)+
		((time==null) ? "" : ";max-age="+time)+";path=/truthmoment/"
	}
	setCookie('truthMomentOpenid','<?php echo $arr["openid"];?>',24*3600*100)
</script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">
	wx.config({
    debug: true,
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: <?php echo $signPackage["timestamp"];?>,
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
      // 所有要调用的 API 都要加到这个列表中
      'checkJsApi',
      'onMenuShareTimeline',
      'onMenuShareAppMessage',
      'onMenuShareQQ',
      'onMenuShareWeibo',
      'hideMenuItems',
      'showMenuItems',
      'hideAllNonBaseMenuItem',
      'showAllNonBaseMenuItem',
      'translateVoice',
      'startRecord',
      'stopRecord',
      'onRecordEnd',
      'playVoice',
      'pauseVoice',
      'stopVoice',
      'uploadVoice',
      'downloadVoice',
      'chooseImage',
      'previewImage',
      'uploadImage',
      'downloadImage',
      'getNetworkType',
      'openLocation',
      'getLocation',
      'hideOptionMenu',
      'showOptionMenu',
      'closeWindow',
      'scanQRCode',
      'chooseWXPay',
      'openProductSpecificView',
      'addCard',
      'chooseCard',
      'openCard'
      ]
    });

wx.ready(function () {
    // 在这里调用 API
    wx.onMenuShareTimeline({
	    title: '<?php echo $arr['nickname']."接受了真心话挑战，匿名提问，表白也行哈哈~你敢问我就敢答"; ?>', // 分享标题
	    link: 'http://item.redream.cn/truthmoment/index.html?id=<?php echo $id;?>&userid=<?php echo $arr["openid"];?>', // 分享链接
	    imgUrl: 'http://wx.qlogo.cn/mmopen/16Q9aDojjfkMRfo7TSc9F94vl7fia8KXJKGYYXRicxqk4aORaiahBqpfAfAc8cxnFicGfEgAXPfWBwMSrprianaQ2BP1om4scpjrl/0', // 分享图标
	    success: function () { 
	        // 用户确认分享后执行的回调函数
	    },
	    cancel: function () { 
	        // 用户取消分享后执行的回调函数
	    }
	});
	wx.onMenuShareAppMessage({
	    title: '<?php echo $arr["nickname"]."接受了真心话挑战，匿名提问，表白也行哈哈~你敢问我就敢答"; ?>', // 分享标题
	    desc: '真心话大挑战，你敢应战否？', // 分享描述
	    link: 'http://item.redream.cn/truthmoment/index.html?id=<?php echo $id;?>&userid=<?php echo $arr["openid"];?>', // 分享链接
	    imgUrl: 'http://wx.qlogo.cn/mmopen/16Q9aDojjfkMRfo7TSc9F94vl7fia8KXJKGYYXRicxqk4aORaiahBqpfAfAc8cxnFicGfEgAXPfWBwMSrprianaQ2BP1om4scpjrl/0', // 分享图标
	    type: '', // 分享类型,music、video或link，不填默认为link
	    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
	    success: function () { 
	        // 用户确认分享后执行的回调函数
	    },
	    cancel: function () { 
	        // 用户取消分享后执行的回调函数
	    }
	});
	wx.onMenuShareQQ({
	    title: '<?php echo $arr['nickname']."接受了真心话挑战，匿名提问，表白也行哈哈~你敢问我就敢答"; ?>', // 分享标题
	    desc: '真心话大挑战，你敢应战否？', // 分享描述
	    link: 'http://item.redream.cn/truthmoment/index.html?id=<?php echo $id;?>&userid=<?php echo $arr["openid"];?>', // 分享链接
	    imgUrl: 'http://wx.qlogo.cn/mmopen/16Q9aDojjfkMRfo7TSc9F94vl7fia8KXJKGYYXRicxqk4aORaiahBqpfAfAc8cxnFicGfEgAXPfWBwMSrprianaQ2BP1om4scpjrl/0', // 分享图标
	    success: function () { 
	       // 用户确认分享后执行的回调函数
	    },
	    cancel: function () { 
	       // 用户取消分享后执行的回调函数
	    }
	});
	wx.onMenuShareWeibo({
	    title: '<?php echo $arr['nickname']."接受了真心话挑战，匿名提问，表白也行哈哈~你敢问我就敢答"; ?>', // 分享标题
	    desc: '真心话大挑战，你敢应战否？', // 分享描述
	    link: 'http://item.redream.cn/truthmoment/index.html?id=<?php echo $id;?>&userid=<?php echo $arr["openid"];?>', // 分享链接
	    imgUrl: 'http://wx.qlogo.cn/mmopen/16Q9aDojjfkMRfo7TSc9F94vl7fia8KXJKGYYXRicxqk4aORaiahBqpfAfAc8cxnFicGfEgAXPfWBwMSrprianaQ2BP1om4scpjrl/0', // 分享图标
	    success: function () { 
	       // 用户确认分享后执行的回调函数
	    },
	    cancel: function () { 
	        // 用户取消分享后执行的回调函数
	    }
	});
	wx.onMenuShareQZone({
	    title: '<?php echo $arr['nickname']."接受了真心话挑战，匿名提问，表白也行哈哈~你敢问我就敢答"; ?>', // 分享标题
	    desc: '真心话大挑战，你敢应战否？', // 分享描述
	    link: 'http://item.redream.cn/truthmoment/index.html?id=<?php echo $id;?>&userid=<?php echo $arr["openid"];?>', // 分享链接
	    imgUrl: 'http://wx.qlogo.cn/mmopen/16Q9aDojjfkMRfo7TSc9F94vl7fia8KXJKGYYXRicxqk4aORaiahBqpfAfAc8cxnFicGfEgAXPfWBwMSrprianaQ2BP1om4scpjrl/0', // 分享图标
	    success: function () { 
	       // 用户确认分享后执行的回调函数
	    },
	    cancel: function () { 
	        // 用户取消分享后执行的回调函数
	    }
	});
  });
</script>
<script>
	<!-- //百度统计 -->
	var _hmt = _hmt || [];
	(function() {
	  var hm = document.createElement("script");
	  hm.src = "//hm.baidu.com/hm.js?f2cc571993a81df123eaa6ef3a5be7c5";
	  var s = document.getElementsByTagName("script")[0]; 
	  s.parentNode.insertBefore(hm, s);
	})();
</script>
</html>