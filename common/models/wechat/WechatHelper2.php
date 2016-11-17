<?php
namespace common\models\wechat;

use Yii;
use callmez\wechat\sdk\Wechat;

class WechatHelper2
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
    public function response()
    {
        /**
         * @var Wechat $wechat
         */
        $wechat = Yii::$app->wechat;

        echo $wechat->getAccessToken();
    }
    
    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)) {

            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);

            switch ($RX_TYPE) {
                case "event":
                    $result = $this->receiveEvent($postObj);
                    break;
                case "text":
                    $result = $this->receiveText($postObj);
                    break;
                case "image":
                    $result = $this->receiveImage($postObj);
                    break;
                case "location":
                    $result = $this->receiveLocation($postObj);
                    break;
                case "voice":
                    $result = $this->receiveVoice($postObj);
                    break;
                case "video":
                    $result = $this->receiveVideo($postObj);
                    break;
                case "link":
                    $result = $this->receiveLink($postObj);
                    break;
                default:
                    $result = "unknown msg type: " . $RX_TYPE;
                    break;
            }
            echo $result;
            exit;
        }
    }

    //接收文本消息
    private function receiveText($object)
    {
//        $openId = $object->FromUserName;
//        $json = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx40f4453940be5dee&secret=e09b251e78c3ac2169b7d1b486b69eab");
//
//        $dataArr = json_decode($json,true);
//        $token = $dataArr['access_token'];
//        $content = file_get_contents("https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$token");
//        return $content;
//        $mp3 = urlencode($object->Content); // 将用户输入信息进行url编码
//        $urlapi = "http://box.zhangmen.baidu.com/x?op=12&count=1&title={$mp3}$$"; // 请求带有歌曲关键词的数据
//        $simstr = file_get_contents($urlapi); // 获取数据
//        $musicobj = simplexml_load_string($simstr); // 解析数据
        switch ($object->Content)
        {
            case "文本":
                $content = "这是个文本消息";
                break;
            case "图文":
            case "单图文":
                $content = array();
                $content[] = array("Title"=>"单图文标题",  "Description"=>"单图文内容", "PicUrl"=>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
                break;
            case "多图文":
                $content = array();
                $content[] = array("Title"=>"多图文1标题", "Description"=>"", "PicUrl"=>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
                $content[] = array("Title"=>"多图文2标题", "Description"=>"", "PicUrl"=>"http://d.hiphotos.bdimg.com/wisegame/pic/item/f3529822720e0cf3ac9f1ada0846f21fbe09aaa3.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
                $content[] = array("Title"=>"多图文3标题", "Description"=>"", "PicUrl"=>"http://g.hiphotos.bdimg.com/wisegame/pic/item/18cb0a46f21fbe090d338acc6a600c338644adfd.jpg", "Url" =>"http://m.cnblogs.com/?u=txw1958");
                break;
            case "音乐":
                $content = array("Title"=>"最炫民族风", "Description"=>"歌手：凤凰传奇", "MusicUrl"=>"http://121.199.4.61/music/zxmzf.mp3", "HQMusicUrl"=>"http://121.199.4.61/music/zxmzf.mp3");
                break;
            default:
                $content = date("Y-m-d H:i:s",time());
                break;
        }
//        $content = $musicobj;
        if(is_array($content)){
            if (isset($content[0]['PicUrl'])){
                $result = $this->transmitNews($object, $content);
            }else if (isset($content['MusicUrl'])){
                $result = $this->transmitMusic($object, $content);
            }
        }else{
            $result = $this->transmitText($object, $content);
        }
        return $result;
    }

    private function receiveEvent($postObj)
    {
        $openId = $postObj->FromUserName;
        $json = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx40f4453940be5dee&secret=e09b251e78c3ac2169b7d1b486b69eab");
        $dataArr = json_decode($json,true);
        $token = $dataArr['access_token'];
        $userjson = file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token=$token&openid=$openId");
//        $dataArr = json_encode($json,true);
        $result = $this->transmitText($postObj, $userjson);
        return $result;
    }

    //回复文本消息
    private function transmitText($object, $content)
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
        $content = $wechat->getQrCodeUrl($wechat->createQrCode($params)['ticket']);
        print_r($content);
        $textTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[text]]></MsgType>
        <Content><![CDATA[%s]]></Content>
        </xml>";
        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $object->msgType,$content);
        return $result;
    }

    //回复图文消息
    private function transmitNews($object, $newsArray)
    {
        if(!is_array($newsArray)){
            return;
        }
        $itemTpl = "    <item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
        </item>
        ";
        $item_str = "";
        foreach ($newsArray as $item){
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $newsTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[news]]></MsgType>
        <Content><![CDATA[]]></Content>
        <ArticleCount>%s</ArticleCount>
        <Articles>
        $item_str</Articles>
        </xml>";
        $result = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $result;
    }

    //回复音乐消息
    private function transmitMusic($object, $musicArray)
    {
        $itemTpl = "<Music>
            <Title><![CDATA[%s]]></Title>
            <Description><![CDATA[%s]]></Description>
            <MusicUrl><![CDATA[%s]]></MusicUrl>
            <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
        </Music>";
                $item_str = sprintf($itemTpl, $musicArray['Title'], $musicArray['Description'], $musicArray['MusicUrl'], $musicArray['HQMusicUrl']);
                $textTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[music]]></MsgType>
            $item_str
        </xml>";
        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复图片消息
    private function transmitImage($object, $imageArray)
    {
        $itemTpl = "<Image>
                <MediaId><![CDATA[%s]]></MediaId>
            </Image>";
                    $item_str = sprintf($itemTpl, $imageArray['MediaId']);
                    $textTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[image]]></MsgType>
            $item_str
            </xml>";
        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    public function getCode()
    {
        echo 'a';exit;
//        if (isset($_GET['code'])){
//            echo $_GET['code'];
//        }else{
//            echo "NO CODE";
//        }
        //https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx40f4453940be5dee&redirect_uri=https://wx.ddgold.cn&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect
    }
}
