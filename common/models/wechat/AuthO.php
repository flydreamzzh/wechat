<?php
namespace common\models\wechat;

use Yii;
use callmez\wechat\sdk\Wechat;

class AuthO
{
    public function valid()
    {
        /**
         * @var Wechat $wechat
         */
        $wechat = Yii::$app->wechat;
        $echoStr = $_GET["echostr"];
        if ($wechat->checkSignature()) {
            echo $echoStr;
            exit();
        }
    }

    public function getCode()
    {
        if (isset($_GET['code'])){
            echo $_GET['code'];
        }else{
            echo "NO CODE";
        }
    }

    public function createEw()
    {
        //永久二维马  https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=df4sd56f4sd53f4f56g4s3df4sd3f4sd53f453
    }

    public function Open()
    {
        //https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8888888888888888&redirect_uri=http://mascot.duapp.com/oauth2.php&response_type=code&scope=snsapi_base&state=1#wechat_redirect
    }

    public function getUserInfo()
    {
        //https://api.weixin.qq.com/cgi-bin/user/info?access_token=ACCESS_TOKEN&openid=OPENID&lang=zh_CN
    }

    public function Xml()
    {
        //未关注
            "<xml>
                <ToUserName><![CDATA[toUser]]></ToUserName>
                <FromUserName><![CDATA[FromUser]]></FromUserName>
                <CreateTime>123456789</CreateTime>
                <MsgType><![CDATA[event]]></MsgType>
                <Event><![CDATA[subscribe]]></Event>
                <EventKey><![CDATA[qrscene_123123]]></EventKey>
                <Ticket><![CDATA[TICKET]]></Ticket>
                </xml>";
        //已关注
        "<xml>
            <ToUserName><![CDATA[toUser]]></ToUserName>
            <FromUserName><![CDATA[FromUser]]></FromUserName>
            <CreateTime>123456789</CreateTime>
            <MsgType><![CDATA[event]]></MsgType>
            <Event><![CDATA[SCAN]]></Event>
            <EventKey><![CDATA[SCENE_VALUE]]></EventKey>
            <Ticket><![CDATA[TICKET]]></Ticket>
            </xml>";
    }

    public function createCode()
    {
        /**
         * @var Wechat $wechat
         */
        $wechat = Yii::$app->wechat;

        $params = [
            // 二维码类型，QR_SCENE为临时,QR_LIMIT_SCENE为永久,QR_LIMIT_STR_SCENE为永久的字符串参数值,默认为QR_SCENE
            'action_name' => 'QR_SCENE',
            'action_info' => [
                'scene' => [
                    // 场景值ID，临时二维码时为32位非0整型，永久二维码时最大值为100000（目前参数只支持1--100000）
                    'scene_id' => 10000,
                    // 场景值ID（字符串形式的ID），字符串类型，长度限制为1到64，仅永久二维码支持此字段
                    'scene_str' => "sssfds"
                ]
            ]
        ];
//         var_dump($wechat->createQrCode($params)) ;
        echo $wechat->getQrCodeUrl($wechat->createQrCode($params)['ticket']);
    }
}
