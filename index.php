<?php
/*
*
*实现类的自动加载
*/
define("ROOT",dirname(__FILE__));
set_include_path(".".PATH_SEPARATOR.ROOT."/wxfunction/".PATH_SEPARATOR.get_include_path());
//__autoload();在实例化的对象没有找到类时会触发；实现动态加载
function __autoload($classname)
{
    require_once("$classname.php");
}

//include "mysql.php";

//微信公众平台基础接口PHP SDK （面向过程版）

define("TOKEN","weixin");
$wechat = new Wechat_base_api("weixin","wx129325db79888ee1","9e21fbec9c5fe8926a4f3ce0c4634529");


class Wechat_base_api{
    private $token;
    private $xmlObject;
    private $postData;
    private $isDebug=false;
    private $accessToken;

    function __construct($token,$appId, $appSecret){
        $jssdk=new JSSDK($appId, $appSecret);
        $this->token=$token;
        $this->accessToken=$jssdk->getAccessToken();
        if ($this->localhostDebug()) {
            
        }else{
            if(!isset($_GET['echostr'])){
            //调用响应消息函数
                $this->responseMsg();
            }else{
                //实现网址接入，调用验证消息函数   
                $this->valid();
            }
        }
        
    }

    private function localhostDebug(){
        if ($_SERVER['HTTP_HOST'] != 'localhost') {
            return false;
        }else{
            $this->isDebug=true;
            echo "localhost visit!<br>";
            $this->postData = "<xml>
             <ToUserName><![CDATA[toUser]]></ToUserName>
             <FromUserName><![CDATA[fromUser]]></FromUserName>
             <CreateTime>1348831860</CreateTime>
             <MsgType><![CDATA[text]]></MsgType>
             <Content><![CDATA[this is a test]]></Content>
             <MsgId>1234567890123456</MsgId>
             </xml>";
            $this->xmlObject = simplexml_load_string($this->postData,"SimpleXMLElement",LIBXML_NOCDATA);
            $this->xmlObject->Content = $_GET['text'];
            $this->responseMsg();
            // echo $this->xmlObject->Content;
            echo "accessToken:$this->accessToken";
            return true;
        }
    }

    //验证消息

    public function valid(){

        if($this->checkSignature())
        {
            $echostr = $_GET["echostr"];
            echo $echostr;
            exit;
        }
        else
        {
            echo "error";
            exit;
        }
    }

    //检查签名
    private function checkSignature()
    {
        //获取微信服务器GET请求的4个参数

        $signature = $_GET['signature'];

        $timestamp = $_GET['timestamp'];

        $nonce = $_GET['nonce'];

        //定义一个数组，存储其中3个参数，分别是timestamp，nonce和token

        $tempArr = array($nonce,$timestamp,$this->token);

        //进行排序

        sort($tempArr,SORT_STRING);

        //将数组转换成字符串

        $tmpStr = implode($tempArr);

        //进行sha1加密算法

        $tmpStr = sha1($tmpStr);

        //判断请求是否来自微信服务器，对比$tmpStr和$signature
        if($tmpStr == $signature)
        {
            return true;
        }
        else
        {
            return false;
        }
    }   


    //响应消息
    public function responseMsg(){
        if (!$this->isDebug) {
            //根据用户传过来的消息类型进行不同的响应

            //1、接收微信服务器POST过来的数据，XML数据包

            $this->postData =  $GLOBALS[HTTP_RAW_POST_DATA];

            if(!$this->postData)
            {
                echo  "postData is empty!";
                exit();
            }

            //2、解析XML数据包
            $this->xmlObject = simplexml_load_string($this->postData,"SimpleXMLElement",LIBXML_NOCDATA);
        }
        // var_dump()
        $xmlObject = $this->xmlObject ;
        //获取消息类型
        $MsgType = $xmlObject->MsgType;

        switch ($MsgType) {

            case 'event':

                    //接收事件推送

                    $this->receiveEvent();   

                break;  

            case 'text':

                    //接收文本消息
            if($xmlObject->Content=='debug'){
                $postData=$this->postData;
                $postData=str_replace('<', '《', $postData);
                $postData=str_replace('>', '》', $postData);
                foreach ($_GET as $key => $value) {
                    $get_str+="($key => $value)";
                }
                foreach ($_POST as $key => $value) {
                    $post_str+="($key => $value)";
                }
                $content="$postData \n get: $get_str \n post: $post_str";

                echo $this->replyText($content); 

                exit;
            }

            if ($xmlObject->Content=='模板') {
                $content=$this->replyTemplate();
                echo $this->replyText($content); 
                // echo $this->replyText($xmlObject->Content); 
                exit;
            }

            $textEvent=new TextEvent($xmlObject);
            $content=$textEvent->response();
            if ($content!='') {
                echo $this->replyText($content); 
            }else{
                echo '';
            }
            
                break;
                    

            case 'image':

                //接收图片消息

                echo $this->receiveImage();  

                break;

            case 'location':

                //接收地理位置消息

                echo $this->receiveLocation();   

                break;  

            case 'voice':

                //接收语音消息

                echo $this->receiveVoice();

                break; 

            case 'video':

                //接收视频消息

                echo $this->receiveVideo();

                break;

            case 'shortvideo':

                //接小收视频消息

                echo $this->receiveVideo();

                break;

            case  'link':

                //接收链接消息

                echo $this->receiveLink();

                break;

            default:

                break;

        }

    }



    //接收事件推送

    private function receiveEvent(){

        switch ($this->xmlObject->Event) {

            //关注事件

            case 'subscribe':

                //扫描带参数的二维码，用户未关注时，进行关注后的事件

                if(!empty($this->xmlObject->EventKey)){

                    //做相关处理

                }

                $dataArray = array(

                    array(

                        "Title"=>"南开漂流瓶欢迎你~~",

                        "Description"=>"南开漂流瓶",

                        "PicUrl"=>"http://www.redream.cn/weixin/1.jpg",

                        "Url"=>"http://mp.weixin.qq.com/s?__biz=MzAxNTA3MDY1NA==&mid=200821619&idx=1&sn=aace3c7fafa64007aaf23bca80181e49#rd"

                        ),

                    array(

                        "Title"=>"漂流瓶操作菜单",

                        "Description"=>"南开漂流瓶",

                        "PicUrl"=>"http://www.redream.cn/weixin/2.jpg",

                        "Url"=>"http://mp.weixin.qq.com/s?__biz=MzAxNTA3MDY1NA==&mid=200821619&idx=2&sn=b9793785c9c312e24c778fc5537cac57#rd"

                        ),

                    array(

                        "Title"=>"发帖须知",

                        "Description"=>"南开漂流瓶",

                        "PicUrl"=>"http://www.redream.cn/weixin/3.jpg",

                        "Url"=>"http://mp.weixin.qq.com/s?__biz=MzAxNTA3MDY1NA==&mid=200821619&idx=3&sn=e13e0ea1d13e41fb07d87fe8bcd18002#rd"

                        ),

                    array(

                        "Title"=>"文章---时光静好，品味孤独的唯美",

                        "Description"=>"南开漂流瓶",

                        "PicUrl"=>"http://www.redream.cn/weixin/4.jpg",

                        "Url"=>"http://mp.weixin.qq.com/s?__biz=MzAxNTA3MDY1NA==&mid=200821619&idx=4&sn=5c31b3d260bbd0776fb968f7a7131115#rd"

                        ),

                    array(

                        "Title"=>"文章---那年夏天",

                        "Description"=>"南开漂流瓶",

                        "PicUrl"=>"http://www.redream.cn/weixin/5.jpg",

                        "Url"=>"http://mp.weixin.qq.com/s?__biz=MzAxNTA3MDY1NA==&mid=200821619&idx=5&sn=c5a379d72d565f0460e68a93b82bd7d8#rd"

                        )

                    );

                

                echo $this->replyNews($dataArray);

                break;

            //取消关注事件

            case 'unsubscribe':

                break;

            //扫描带参数的二维码，用户已关注时，进行关注后的事件

            case 'SCAN':

                //做相关的处理

                break;

            //自定义菜单事件

            case 'CLICK':

                //

                switch ($this->xmlObject->EventKey) {

                    case 'FAQ':

                        echo $this->replyText("你的点击的是FAQ事件");

                        break;

                    default:

                        echo $this->replyText("你的点击的是其他事件");

                        break;

                }

                break;

            //点击菜单跳转链接时的事件推送
            case 'VIEW':

                //做相关的处理

                break;

        }   

    }

    //接收文本消息

    private function receiveText(){

        //获取文本消息的内容

        $content = $this->xmlObject->Content;

        //发送文本消息

        return $this->replyText($content);

    }

    //接收图片消息

    private function receiveImage()

    {

        //获取图片消息的内容

        $imageArr = array(

            "PicUrl"=>$this->xmlObject->PicUrl,

            "MediaId"=>$this->xmlObject->MediaId

            );

        //发送图片消息

        return $this->replyImage($imageArr);

    }

    //接收地理位置消息

    private function receiveLocation()

    {

        //获取地理位置消息的内容

        $locationArr = array(

                "Location_X"=>$this->xmlObject->Location_X,

                "Location_Y"=>"地址位置经度：".$this->xmlObject->Location_Y,

                "Label"=>$this->xmlObject->Label

            );

        //回复文本消息

        return $this->replyText($locationArr['Location_Y']);   

    }

    //接收语言消息

    private function receiveVoice(){

        //获取语言消息内容

        $voiceArr = array(

                "MediaId"=>$this->xmlObject->MediaId,

                "Format"=>$this->xmlObject->Format

            );

        //回复语言消息

        return $this->replyVoice($voiceArr);

    }

    //接收视频消息

    private function receiveVideo(){

        //获取视频消息的内容

        $videoArr = array(

                "MediaId"=>$this->xmlObject->MediaId 

            );

        //回复视频消息

        return $this->replyVideo($videoArr);           

    }

    //接收链接消息

    private function receiveLink()

    {

        //接收链接消息的内容

        $linkArr = array(

                "Title"=>$this->xmlObject->Title,

                "Description"=>$this->xmlObject->Description,

                "Url"=>$this->xmlObject->Url

            );

        //回复文本消息

        return $this->replyText("你发过来的链接地址是{$linkArr['Url']}");

    }

    //发送文本消息

    private function replyText($content,$ToUserName=''){
        $xmlObject=$this->xmlObject;
        if($ToUserName==''){

            $ToUserName=$xmlObject->FromUserName;

        }

        $replyXml = "<xml>

                    <ToUserName><![CDATA[%s]]></ToUserName>

                    <FromUserName><![CDATA[%s]]></FromUserName>

                    <CreateTime>%s</CreateTime>

                    <MsgType><![CDATA[text]]></MsgType>

                    <Content><![CDATA[%s]]></Content>

                    </xml>";

            //返回一个进行xml数据包

        $resultStr = sprintf($replyXml,$xmlObject->FromUserName,$xmlObject->ToUserName,time(),$content);

        return $resultStr;      

    }

    //发送图片消息

    private function replyImage($imageArr){
        $xmlObject=$this->xmlObject;

        $replyXml = "<xml>

                    <ToUserName><![CDATA[%s]]></ToUserName>

                    <FromUserName><![CDATA[%s]]></FromUserName>

                    <CreateTime>%s</CreateTime>

                    <MsgType><![CDATA[image]]></MsgType>

                    <Image>

                    <MediaId><![CDATA[%s]]></MediaId>

                    </Image>

                    </xml>";

            //返回一个进行xml数据包

        $resultStr = sprintf($replyXml,$xmlObject->FromUserName,$xmlObject->ToUserName,time(),$imageArr['MediaId']);

        return $resultStr;          

    }

    //回复语音消息

    private function replyVoice($voiceArr)

    {
        $xmlObject=$this->xmlObject;
        $replyXml = "<xml>

                    <ToUserName><![CDATA[%s]]></ToUserName>

                    <FromUserName><![CDATA[%s]]></FromUserName>

                    <CreateTime>%s</CreateTime>

                    <MsgType><![CDATA[voice]]></MsgType>

                    <Voice>

                    <MediaId><![CDATA[%s]]></MediaId>

                    </Voice>

                    </xml>";

            //返回一个进行xml数据包



        $resultStr = sprintf($replyXml,$xmlObject->FromUserName,$xmlObject->ToUserName,time(),$voiceArr['MediaId']);

        return $resultStr;      

    }



    //回复视频消息

    private function replyVideo($videoArr){
        $xmlObject=$this->xmlObject;
        $replyXml = "<xml>

                    <ToUserName><![CDATA[%s]]></ToUserName>

                    <FromUserName><![CDATA[%s]]></FromUserName>

                    <CreateTime>%s</CreateTime>

                    <MsgType><![CDATA[video]]></MsgType>

                    <Video>

                    <MediaId><![CDATA[%s]]></MediaId>

                    </Video> 

                    </xml>";

            //返回一个进行xml数据包

        $resultStr = sprintf($replyXml,$xmlObject->FromUserName,$xmlObject->ToUserName,time(),$videoArr['MediaId']);

        return $resultStr;

    }



    //回复链接消息

    private function  replyLink($linkArr){
        $xmlObject=$this->xmlObject;
        $replyXml = "<xml>

                    <ToUserName><![CDATA[%s]]></ToUserName>

                    <FromUserName><![CDATA[%s]]></FromUserName>

                    <CreateTime>%s</CreateTime>

                    <MsgType><![CDATA[link]]></MsgType>

                    <Title><![CDATA[%s]]></Title>

                    <Description><![CDATA[%s]]></Description>

                    <Url><![CDATA[%s]]></Url>

                    <MsgId>1234567890123456</MsgId>

                    </xml>";

        //MsgId存在问题

        $resultStr = sprintf($replyXml,$xmlObject->FromUserName,$xmlObject->ToUserName,time(),$linkArr['Title'],$linkArr['Description'],$linkArr['Url']);

        return $resultStr;

    }



    //回复音乐消息

    private function  replyMusic($musicArr)

    {
        $xmlObject=$this->xmlObject;
        $replyXml = "<xml>

                    <ToUserName><![CDATA[%s]]></ToUserName>

                    <FromUserName><![CDATA[%s]]></FromUserName>

                    <CreateTime>%s</CreateTime>

                    <MsgType><![CDATA[music]]></MsgType>

                    <Music>

                    <Title><![CDATA[%s]]></Title>

                    <Description><![CDATA[%s]]></Description>

                    <MusicUrl><![CDATA[%s]]></MusicUrl>

                    <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>

                    <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>

                    </Music>

                    </xml>";

            //返回一个进行xml数据包



        $resultStr = sprintf($replyXml,$xmlObject->FromUserName,$xmlObject->ToUserName,time(),$musicArr['Title'],$musicArr['Description'],$musicArr['MusicUrl'],$musicArr['HQMusicUrl'],$musicArr['ThumbMediaId']);

        return $resultStr;      

    }



    //回复图文消息

    private function replyNews($newsArr){
        $xmlObject=$this->xmlObject;
        $itemStr = "";

        if(is_array($newsArr))

        {

            foreach($newsArr as $item)

            {

                $itemXml ="<item>

                    <Title><![CDATA[%s]]></Title> 

                    <Description><![CDATA[%s]]></Description>

                    <PicUrl><![CDATA[%s]]></PicUrl>

                    <Url><![CDATA[%s]]></Url>

                    </item>";

                $itemStr .= sprintf($itemXml,$item['Title'],$item['Description'],$item['PicUrl'],$item['Url']);

            }

        }

        $replyXml = "<xml>

                    <ToUserName><![CDATA[%s]]></ToUserName>

                    <FromUserName><![CDATA[%s]]></FromUserName>

                    <CreateTime>%s</CreateTime>

                    <MsgType><![CDATA[news]]></MsgType>

                    <ArticleCount>%s</ArticleCount>

                    <Articles>

                        {$itemStr}

                    </Articles>

                    </xml> ";

            //返回一个进行xml数据包

        $resultStr = sprintf($replyXml,$xmlObject->FromUserName,$xmlObject->ToUserName,time(),count($newsArr));

        return $resultStr;          

    }

    /*
    {
       "touser":"OPENID",
       "template_id":"ngqIpbwh8bUfcSsECmogfXcV14J0tQlEpBO27izEYtY",
       "url":"http://weixin.qq.com/download",            
       "data":{
           "first": {
               "value":"恭喜你购买成功！",
               "color":"#173177"
           },
           "keyword1":{
               "value":"巧克力",
               "color":"#173177"
           },
           "keyword2": {
               "value":"39.8元",
               "color":"#173177"
           },
           "keyword3": {
               "value":"2014年9月22日",
               "color":"#173177"
           },
           "remark":{
               "value":"欢迎再次购买！",
               "color":"#173177"
           }
       }
    }
    */
    private function replyTemplate(){
        $templateJson='{
           "touser":"",
           "template_id":"",
           "url":"",            
           "data":{
               "first": {
                   "value":"",
                   "color":""
               },
               "keyword1":{
                   "value":"",
                   "color":""
               },
               "keyword2": {
                   "value":"",
                   "color":""
               },
               "keyword3": {
                   "value":"",
                   "color":""
               },
               "remark":{
                   "value":"",
                   "color":""
               }
           }
        }';
        $xmlObject=$this->xmlObject;
        $template=json_decode($templateJson,true);
        //这里不”“。转换成字符串不识别，难道转换过来的类型不是字符串？
        $template["touser"]="".$this->xmlObject->FromUserName;
        // $template["touser"]="on0bXjiMkvuZgFA320MSMC_JWetw";
        $template["template_id"]="sJx0OByifymwSKSz-h6dNrb6QqDaEMPqz40QtXGRQek";
        $template["url"]="http://redream.cn";
        $template["data"]["first"]["value"]="欢迎来到南开一梦";
        $template["data"]["first"]["color"]="#798000";
        $template["data"]["keyword1"]["value"]="So的一封信";
        $template["data"]["keyword1"]["color"]="#798000";
        // $template["data"]["keyword2"]["value"]="等你审批";
        $template["data"]["keyword2"]["value"]="--".$xmlObject->FromUserName;
        $template["data"]["keyword2"]["color"]="#798000";
        $template["data"]["keyword3"]["value"]="2016年11月14日";
        $template["data"]["keyword3"]["color"]="#798000";
        $template["data"]["remark"]["value"]="期待你~";
        $template["data"]["remark"]["color"]="#798000";

        $templateJson=json_encode($template);
        $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$this->accessToken;
        return $this->httpPost($url,$templateJson);

    }

    private function httpPost($url,$data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //微信官方设置是不对的
        curl_setopt($ch, 2, true);

        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $res = curl_exec($ch);
        curl_close($ch);
        // print_r($data);
        // var_dump($res);
        return $res;
    }

}