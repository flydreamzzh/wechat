<?php
namespace common\models\wechat;

use yii\base\Object;

class RsponseType extends Object
{
    /** 
     * 文本消息:
     *  ToUserName	开发者微信号
        FromUserName	发送方帐号（一个OpenID）
        CreateTime	消息创建时间 （整型）
        MsgType	text
        Content	文本消息内容
        MsgId	消息id，64位整型
     */
    public function responseText()
    {
        $this->text = "
                <xml>
                     <ToUserName><![CDATA[toUser]]></ToUserName>
                     <FromUserName><![CDATA[fromUser]]></FromUserName> 
                     <CreateTime>1348831860</CreateTime>
                     <MsgType><![CDATA[text]]></MsgType>
                     <Content><![CDATA[this is a test]]></Content>
                     <MsgId>1234567890123456</MsgId>
                </xml>";
    }
    
    /**
     * 图片消息:
     *  ToUserName	开发者微信号
        FromUserName	发送方帐号（一个OpenID）
        CreateTime	消息创建时间 （整型）
        MsgType	image
        PicUrl	图片链接
        MediaId	图片消息媒体id，可以调用多媒体文件下载接口拉取数据。
        MsgId	消息id，64位整型
     */
    public function responseImage()
    {
        $this->image = "
                <xml>
                    <ToUserName><![CDATA[toUser]]></ToUserName>
                    <FromUserName><![CDATA[fromUser]]></FromUserName>
                    <CreateTime>1348831860</CreateTime>
                    <MsgType><![CDATA[image]]></MsgType>
                    <PicUrl><![CDATA[this is a url]]></PicUrl>
                    <MediaId><![CDATA[media_id]]></MediaId>
                    <MsgId>1234567890123456</MsgId>
                </xml>";
    }
    
    /**
     * 语音消息:
     *  ToUserName	开发者微信号
        FromUserName	发送方帐号（一个OpenID）
        CreateTime	消息创建时间 （整型）
        MsgType	语音为voice
        MediaId	语音消息媒体id，可以调用多媒体文件下载接口拉取数据。
        Format	语音格式，如amr，speex等
        MsgID	消息id，64位整型
     */
    public function responseVoice()
    {
        $this->voice = "
                <xml>
                    <ToUserName><![CDATA[toUser]]></ToUserName>
                    <FromUserName><![CDATA[fromUser]]></FromUserName>
                    <CreateTime>1357290913</CreateTime>
                    <MsgType><![CDATA[voice]]></MsgType>
                    <MediaId><![CDATA[media_id]]></MediaId>
                    <Format><![CDATA[Format]]></Format>
                    <MsgId>1234567890123456</MsgId>
                </xml>";
    }
    
    /**
     * 视频消息:
     *  ToUserName	开发者微信号
        FromUserName	发送方帐号（一个OpenID）
        CreateTime	消息创建时间 （整型）
        MsgType	视频为video
        MediaId	视频消息媒体id，可以调用多媒体文件下载接口拉取数据。
        ThumbMediaId	视频消息缩略图的媒体id，可以调用多媒体文件下载接口拉取数据。
        MsgId	消息id，64位整型
     */
    public function responseVideo()
    {
        $this->video = "
                <xml>
                    <ToUserName><![CDATA[toUser]]></ToUserName>
                    <FromUserName><![CDATA[fromUser]]></FromUserName>
                    <CreateTime>1357290913</CreateTime>
                    <MsgType><![CDATA[video]]></MsgType>
                    <MediaId><![CDATA[media_id]]></MediaId>
                    <ThumbMediaId><![CDATA[thumb_media_id]]></ThumbMediaId>
                    <MsgId>1234567890123456</MsgId>
                </xml>";
    }
    
    /**
     * 小视频消息:
     *  ToUserName	开发者微信号
        FromUserName	发送方帐号（一个OpenID）
        CreateTime	消息创建时间 （整型）
        MsgType	小视频为shortvideo
        MediaId	视频消息媒体id，可以调用多媒体文件下载接口拉取数据。
        ThumbMediaId	视频消息缩略图的媒体id，可以调用多媒体文件下载接口拉取数据。
        MsgId	消息id，64位整型
     */
    public function responseShortvideo()
    {
        $this->shortvideo = "
                <xml>
                    <ToUserName><![CDATA[toUser]]></ToUserName>
                    <FromUserName><![CDATA[fromUser]]></FromUserName>
                    <CreateTime>1357290913</CreateTime>
                    <MsgType><![CDATA[shortvideo]]></MsgType>
                    <MediaId><![CDATA[media_id]]></MediaId>
                    <ThumbMediaId><![CDATA[thumb_media_id]]></ThumbMediaId>
                    <MsgId>1234567890123456</MsgId>
                </xml>";
    }
    
    /**
     * 地理位置消息:
     *  ToUserName	开发者微信号
        FromUserName	发送方帐号（一个OpenID）
        CreateTime	消息创建时间 （整型）
        MsgType	location
        Location_X	地理位置维度
        Location_Y	地理位置经度
        Scale	地图缩放大小
        Label	地理位置信息
        MsgId	消息id，64位整型
     */
    public function responseLocation()
    {
        $this->location = "
                <xml>
                    <ToUserName><![CDATA[toUser]]></ToUserName>
                    <FromUserName><![CDATA[fromUser]]></FromUserName>
                    <CreateTime>1351776360</CreateTime>
                    <MsgType><![CDATA[location]]></MsgType>
                    <Location_X>23.134521</Location_X>
                    <Location_Y>113.358803</Location_Y>
                    <Scale>20</Scale>
                    <Label><![CDATA[位置信息]]></Label>
                    <MsgId>1234567890123456</MsgId>
                </xml>";
    }
    
    /**
     * 链接消息:
     *  ToUserName	接收方微信号
        FromUserName	发送方微信号，若为普通用户，则是一个OpenID
        CreateTime	消息创建时间
        MsgType	消息类型，link
        Title	消息标题
        Description	消息描述
        Url	消息链接
        MsgId	消息id，64位整型
     */
    public function responseLink()
    {
        $this->link = "
                <xml>
                    <ToUserName><![CDATA[toUser]]></ToUserName>
                    <FromUserName><![CDATA[fromUser]]></FromUserName>
                    <CreateTime>1351776360</CreateTime>
                    <MsgType><![CDATA[link]]></MsgType>
                    <Title><![CDATA[公众平台官网链接]]></Title>
                    <Description><![CDATA[公众平台官网链接]]></Description>
                    <Url><![CDATA[url]]></Url>
                    <MsgId>1234567890123456</MsgId>
                </xml>";
    }
}  