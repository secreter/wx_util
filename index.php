<?php

//include "mysql.php";

//微信公众平台基础接口PHP SDK （面向过程版）

define("TOKEN","weixin");
$wechat = new Wechat_base_api();
if(!isset($_GET['echostr']))
{
    //调用响应消息函数
    $wechat->responseMsg();
}
else
{
    //实现网址接入，调用验证消息函数   
    $wechat->valid();
}

class Wechat_base_api{

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

        $tempArr = array($nonce,$timestamp,TOKEN);

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

        //根据用户传过来的消息类型进行不同的响应

        //1、接收微信服务器POST过来的数据，XML数据包

        $postData = $GLOBALS[HTTP_RAW_POST_DATA];

        if(!$postData)

        {

            echo  "error";

            exit();

        }



        //2、解析XML数据包

        $object = simplexml_load_string($postData,"SimpleXMLElement",LIBXML_NOCDATA);

        //获取消息类型

        $MsgType = $object->MsgType;

        switch ($MsgType) {

            case 'event':

                    //接收事件推送

                    $this->receiveEvent($object);   

                break;  

            case 'text':

                    //接收文本消息
            if($object->Content=='debug'){
                $postData=str_replace('<', '《', $postData);
                $postData=str_replace('>', '》', $postData);
                foreach ($_GET as $key => $value) {
                    $get_str+="($key => $value)";
                }
                foreach ($_POST as $key => $value) {
                    $post_str+="($key => $value)";
                }
                $content="$postData \n get: $get_str \n post: $post_str";

                echo $this->replyText($object,$content); 

                exit;
            }


            if($object->Content=='菜单'){

                $content='*回复“菜单”，显示本操作菜单。

                            *直接发消息，表示发出一条树洞状态，状态字符数必须大于7个字符并且小于140个字符。为防止水树洞，每条状态时间间隔为3分钟。

                            *回复“回复[7]***”，***代表消息，表示回复编号为7的树洞消息，消息将回复到指定树洞状态下方，所有人可见。

                            *回复“树洞”，查看最近5条树洞状态和回复。

                            *回复“树洞[7]”，查看从7-12其中5条状态和回复。

                            *回复“丢瓶子[***]”,***代表消息，表示丢出一个漂流瓶，漂流瓶字符数必须大于7个字符并且小于140个字符。

                            *回复“回复瓶子[7]***”，***代表消息，表示回复编号为7的漂流瓶消息，消息将回复到指定漂流瓶下方，捞到瓶子的人可见。

                            *回复“捞瓶子”，随机捞取一个漂流瓶。

                            *回复“捞瓶子[7]”，捞取指定编号为7的漂流瓶。

                            *回复“笑话”随机生成笑话。

                            *回复“糗事”随机生成糗事。

                            *回复“微小说”随机生成微小说。

                            *回复“文章”随机生成one和redream文章           

							*回复‘表白’进入表白墙；

							*回复‘心情’进入心情簿；

							*回复‘许愿’进入许愿墙；

							*回复‘广场’进入南开广场；

							';

                         echo $this->replyText($object,$content); 

                         exit;

                         }   



                    echo $this->replyText($object,$content); 

                        break;
                    

            case 'image':

                        //接收图片消息

                        echo $this->receiveImage($object);  

                break;

            case 'location':

                        //接收地理位置消息

                        echo $this->receiveLocation($object);   

                break;  

            case 'voice':

                    //接收语音消息

                    echo $this->receiveVoice($object);

                break; 

            case 'video':

                    //接收视频消息

                    echo $this->receiveVideo($object);

                break;

            case  'link':

                    //接收链接消息

                    echo $this->receiveLink($object);

                    break;

            default:

                break;

        }

    }



    //接收事件推送

    private function receiveEvent($obj){

        switch ($obj->Event) {

            //关注事件

            case 'subscribe':

                //扫描带参数的二维码，用户未关注时，进行关注后的事件

                if(!empty($obj->EventKey)){

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

                

                echo $this->replyNews($obj,$dataArray);



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

                switch ($obj->EventKey) {

                    case 'FAQ':

                        echo $this->replyText($obj,"你的点击的是FAQ事件");

                        break;

                    default:

                        echo $this->replyText($obj,"你的点击的是其他事件");

                        break;

                }

                break;

        }   

    }



    //接收文本消息

    private function receiveText($obj){

        //获取文本消息的内容

        $content = $obj->Content;

        //发送文本消息

        return $this->replyText($obj,$content);

    }



    //接收图片消息

    private function receiveImage($obj)

    {

        //获取图片消息的内容

        $imageArr = array(

            "PicUrl"=>$obj->PicUrl,

            "MediaId"=>$obj->MediaId

            );

        //发送图片消息

        return $this->replyImage($obj,$imageArr);

    }



    //接收地理位置消息

    private function receiveLocation($obj)

    {

        //获取地理位置消息的内容

        $locationArr = array(

                "Location_X"=>$obj->Location_X,

                "Location_Y"=>"地址位置经度：".$obj->Location_Y,

                "Label"=>$obj->Label

            );

        //回复文本消息

        return $this->replyText($obj,$locationArr['Location_Y']);   

    }



    //接收语言消息

    private function receiveVoice($obj){

        //获取语言消息内容

        $voiceArr = array(

                "MediaId"=>$obj->MediaId,

                "Format"=>$obj->Format

            );

        //回复语言消息

        return $this->replyVoice($obj,$voiceArr);

    }



    //接收视频消息

    private function receiveVideo($obj){

        //获取视频消息的内容

        $videoArr = array(

                "MediaId"=>$obj->MediaId 

            );

        //回复视频消息

        return $this->replyVideo($obj,$videoArr);           

    }



    //接收链接消息

    private function receiveLink($obj)

    {

        //接收链接消息的内容

        $linkArr = array(

                "Title"=>$obj->Title,

                "Description"=>$obj->Description,

                "Url"=>$obj->Url

            );

        //回复文本消息

        return $this->replyText($obj,"你发过来的链接地址是{$linkArr['Url']}");

    }



    //发送文本消息

    private function replyText($obj,$content,$ToUserName=''){

        if($ToUserName==''){

            $ToUserName=$obj->FromUserName;

        }

        $replyXml = "<xml>

                    <ToUserName><![CDATA[%s]]></ToUserName>

                    <FromUserName><![CDATA[%s]]></FromUserName>

                    <CreateTime>%s</CreateTime>

                    <MsgType><![CDATA[text]]></MsgType>

                    <Content><![CDATA[%s]]></Content>

                    </xml>";

            //返回一个进行xml数据包



        $resultStr = sprintf($replyXml,$ToUserName,$obj->ToUserName,time(),$content);

            return $resultStr;      

    }



    //发送图片消息

    private function replyImage($obj,$imageArr){

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



        $resultStr = sprintf($replyXml,$obj->FromUserName,$obj->ToUserName,time(),$imageArr['MediaId']);

            return $resultStr;          

    }



    //回复语音消息

    private function replyVoice($obj,$voiceArr)

    {

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



        $resultStr = sprintf($replyXml,$obj->FromUserName,$obj->ToUserName,time(),$voiceArr['MediaId']);

            return $resultStr;      

    }



    //回复视频消息

    private function replyVideo($obj,$videoArr){

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



        $resultStr = sprintf($replyXml,$obj->FromUserName,$obj->ToUserName,time(),$videoArr['MediaId']);

            return $resultStr;

    }



    //回复链接消息

    private function  replyLink($obj,$linkArr){

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

                    $resultStr = sprintf($replyXml,$obj->FromUserName,$obj->ToUserName,time(),$linkArr['Title'],$linkArr['Description'],$linkArr['Url']);

            return $resultStr;

    }



    //回复音乐消息

    private function  replyMusic($obj,$musicArr)

    {

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



        $resultStr = sprintf($replyXml,$obj->FromUserName,$obj->ToUserName,time(),$musicArr['Title'],$musicArr['Description'],$musicArr['MusicUrl'],$musicArr['HQMusicUrl'],$musicArr['ThumbMediaId']);

            return $resultStr;      

    }



    //回复图文消息

    private function replyNews($obj,$newsArr){

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



        $resultStr = sprintf($replyXml,$obj->FromUserName,$obj->ToUserName,time(),count($newsArr));

            return $resultStr;          

    }

}