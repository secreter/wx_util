<?php
require_once "jssdk.php";
$jssdk = new JSSDK("wx129325db79888ee1", "9e21fbec9c5fe8926a4f3ce0c4634529");
$signPackage = $jssdk->GetSignPackage();
$access_token=$jssdk->getAccessToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title></title>
  <style type="text/css">
    button{
      display: block;
      width: 80%;
      height: 100px;
      margin: 20px auto;
      background: #280;
      opacity: 0.7;
      font-size: 24px;
      border-radius: 3px;
    }
  </style>
</head>
<body>
  <button id="btn1">分享到朋友圈</button>
  <button id="btn2">分享给好友</button>
  <button id="btn3">上传照片</button>
  <div id="img1"></div>

  <button id="btn4">下载照片</button>
  <button id="btn5">下载到服务器</button>
  <button id="btn6">分享到朋友圈</button>
  <button id="btn7">分享到朋友圈</button>
  <button id="btn8">分享到朋友圈</button>
</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="jquery.min.js"></script>
<script>
  function g(id){
    return document.getElementById(id);
  }
  var downloadId;
  /*
   * 注意：
   * 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
   * 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
   * 3. 常见问题及完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
   *
   * 开发中遇到问题详见文档“附录5-常见错误及解决办法”解决，如仍未能解决可通过以下渠道反馈：
   * 邮箱地址：weixin-open@qq.com
   * 邮件主题：【微信JS-SDK反馈】具体问题
   * 邮件内容说明：用简明的语言描述问题所在，并交代清楚遇到该问题的场景，可附上截屏图片，微信团队会尽快处理你的反馈。
   */
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


  });
g("btn1").onclick=function(){
  wx.onMenuShareTimeline({
        title: '分享标题', // 分享标题
        link: 'www.redream.cn', // 分享链接
        imgUrl: 'http://g.hiphotos.baidu.com/image/h%3D360/sign=4c1c77160ed162d99aee641a21dea950/b7003af33a87e9507b3ecabe14385343fbf2b432.jpg', // 分享图标
        // success: function () { 
        //     // 用户确认分享后执行的回调函数
        //     alert("success");
        // },
        // cancel: function () { 
        //     // 用户取消分享后执行的回调函数
        //     alert("cancel");
        // }
        trigger: function (res) {
          alert('用户点击发送给朋友');
        },
        success: function (res) {
          alert('已分享');
        },
        cancel: function (res) {
          alert('已取消');
        },
        fail: function (res) {
          alert(JSON.stringify(res));
        }
      });
  alert('已注册获取“发送给朋友”状态事件');
};


g("btn2").addEventListener(
  "click",
  wx.onMenuShareAppMessage({
      title: '【游戏】一直跑下去', // 分享标题
      desc: '人生就应该always running，敢来挑战吗？', // 分享描述
      link: 'www.redream.cn', // 分享链接
      imgUrl: 'http://c.hiphotos.baidu.com/image/h%3D200/sign=52127e1b553d269731d30f5d65f9b24f/0dd7912397dda1445fca2f63b6b7d0a20df48624.jpg', // 分享图标
      type: 'link', // 分享类型,music、video或link，不填默认为link
      dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
      success: function () { 
          // 用户确认分享后执行的回调函数
        },
        cancel: function () { 
          // 用户取消分享后执行的回调函数
        }
      }),
  false
  );
g("btn3").addEventListener(
  "click",
  function(){
    wx.chooseImage({
      count: 9, // 默认9
      sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
      sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
      success: function (res) {
          var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
          //动态显示
          var str="";
          for (var i = 0; i < localIds.length; i++) {
            //上传图片
            wx.uploadImage({
                localId: localIds[i], // 需要上传的图片的本地ID，由chooseImage接口获得
                isShowProgressTips: 1, // 默认为1，显示进度提示
                success: function (res) {
                    var serverId = res.serverId; // 返回图片的服务器端ID
                    downloadId=serverId;  //全局变量，让其他函数用

                  }
                });
            str+="<img src="+localIds[i]+">"
          };
          g("img1").innerHTML=str;


        }
      })
  },
  false
  );
g("btn4").onclick=function(){
    //document.write(downloadId);
    wx.downloadImage({
            serverId: "hzZ7gkzCnYBWUmr9pOnY3-NjBFddsYWQIfQ9K5W4F9YRVi5fDLZoEzyskHQm0gCt", // 需要下载的图片的服务器端ID，由uploadImage接口获得
            isShowProgressTips: 1, // 默认为1，显示进度提示
            success: function (res) {
                var localId = res.localId; // 返回图片下载后的本地ID
               document.write(window.downloadId);
            }
            // trigger: function (res) {
            //   alert('用户点击发送给朋友');
            // },
            // success: function (res) {
            //   var localId = res.localId; // 返回图片下载后的本地ID
            //   alert(localId);
            // },
            // cancel: function (res) {
            //   alert('已取消');
            // },
            // fail: function (res) {
            //   alert(JSON.stringify(res));
            // }
        });
    alert('已注册获取“下载图片”状态事件');
}

g("btn5").onclick=function(){
  token=<?php echo "'".$access_token."'";?>;

  $.ajax({
   type: "POST",
   url: "download.php",
   data: "access_token="+token+"&media_id="+window.downloadId+"&type=jpg",
   success: function(msg){
     alert( downloadId+"Data Saved: " + msg );
     document.write(msg);
   }
});
}
</script>
</html>
