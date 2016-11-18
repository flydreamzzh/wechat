<?php
namespace common\models\wechat;

use Yii;
use callmez\wechat\sdk\Wechat;
use yii\base\Object;

class WechatModel extends Object
{
    /**
     * 返回Wechat帮助类
     * @return Wechat $_wechat
     */
    public function wechat()
    {
        $_wechat = Yii::$app->wechat;
        return $_wechat;
    }
    
    /**
     * 验证token
     */
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if ($this->wechat()->checkSignature()) {
            echo $echoStr;
            exit();
        }
    }
    
    /**
     * 根据用户信息返回相关信息
     */
    public function responseMsg()
    {
        $postObj = $this->wechat()->parseRequestData(); // 获取post数据
        if (!$postObj) { // 判断获取到的数据是否为空
            $reType = trim($postObj->MsgType);
            switch ($reType) {
                case "text": // 消息类型，text为普通文本类型
                    $resultStr = $this->handleText($postObj);
                    break;
                case "image":
                    $resultStr = $this->handleImg($postObj);
                    break;
                case "voice":
                    $resultStr = $this->handleVio($postObj);
                    break;
                case "location":
                    $resultStr = $this->handleLoc($postObj);
                    break;
                case "event":
                    $resultStr = $this->handleEvent($postObj);
                    break;
                default:
                    $resultStr = "其他消息类型或格式错误: " . $reType; // 不符合上述类型或格式错误的提示
                    break;
            }
            echo $resultStr;
        } else {
            echo "未获取到数据"; // 未获取到数据的提示
            exit(); // 结束程序执行
        }
    }

    /**
     * 验证微信服务器
     */
    private function checkSignature() // 验证消息真实性函数，详见微信开发文档
    {
        $signature = $_GET["signature"]; // 微信加密签名
        $timestamp = $_GET["timestamp"]; // 时间戳
        $nonce = $_GET["nonce"]; // 随机数
        $token = TOKEN; // token与上面对应
        $tmpArr = array(
            $token,
            $timestamp,
            $nonce
        ); // 组装创建数组
        sort($tmpArr, SORT_STRING); // 把$tmpArr做字符串处理并排序(升序)
        $tmpStr = implode($tmpArr); // 将数组变成字符串
        $tmpStr = sha1($tmpStr); // 计算字符串散列
        if ($tmpStr == $signature) { // 判断是否一致
            return true; // 符合返回真
        } else {
            return false; // 否则返回假
        }
    }

    /**
     * 封装微信回复格式
     */
    function XmlTpl()
    { // 微信普通文本消息的XML格式
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                    </xml>";
        return $textTpl;
    }

    function handleText($postObj)
    { // 消息关键词处理函数
        $fromUsername = $postObj->FromUserName; // 获取开发者微信号
        $toUsername = $postObj->ToUserName; // 用户的OpenID
        $keyword = trim($postObj->Content); // 用户输入的信息
        $time = time(); // 时间
        if (mb_substr($keyword, 0, 2, 'utf-8') == "点歌") { // 判断关键词前两个字为“点歌”
            $keyword = mb_substr($keyword, 2, 20, 'utf-8'); // 删除点歌，保留关键信息
            $mucTpl = "<xml>  
                        <Music>
                            <Title><![CDATA[%s]]></Title>
                            <Description><![CDATA[%s]]></Description>
                            <MusicUrl><![CDATA[%s]]></MusicUrl>
                            <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
                        </Music>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                    </xml>"; // 音乐消息的XML格式
            $mp3 = urlencode($keyword); // 将用户输入信息进行url编码
            $urlapi = "http://box.zhangmen.baidu.com/x?op=12&count=1&title={$mp3}$$"; // 请求带有歌曲关键词的数据
            $simstr = file_get_contents($urlapi); // 获取数据
            $musicobj = simplexml_load_string($simstr); // 解析数据
            foreach ($musicobj->url as $itemobj) // 循环遍历数组
            {
                $encode = $itemobj->encode;
                $decode = $itemobj->decode;
                $removedecode = end(explode('&', $decode)); // 按"&"分割成数组
                if ($removedecode != "") // 若不等于空
                {
                    $removedecode = "&" . $removedecode; // 则加上"&"符号
                }
                $decode = str_replace($removedecode, "", $decode); // 字符串替换函数
                $musicurl = str_replace(end(explode('/', $encode)), $decode, $encode);
                // $Media = "";
                break;
            }
            if (! empty($keyword)) // 判断关键词是否为空
            {
                $msgType = "music"; // 消息类型
                $Description = "百度音乐-" . $keyword; // 描述
                $resultStr = sprintf($mucTpl, $keyword, $Description, $musicurl, $musicurl, $toUsername, $fromUsername, $time, $msgType); // 组装回复
                echo $resultStr; // 输出信息给用户
                return; // 回调，反正输出其他信息干扰
            } else {
                $msgType = "text"; // 消息类型，text为普通文本消息
                $contentStr = "请输入点歌+歌名，例如：点歌小苹果"; // 否则，输出提示
                $resultStr = sprintf($this->XmlTpl(), $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
                return ;
            }
        }
        $textTpl = $this->XmlTpl(); // 消息格式。调用函数
        if (! empty($keyword)) {
            $msgType = "text";
            $contentStr = $this->ReText($fromUsername, $keyword);
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr); // 组装回复
            echo $resultStr;
        }
    }

    function handleImg($postObj)
    {
        $fromUsername = $postObj->FromUserName; // 同上
        $toUsername = $postObj->ToUserName;
        $keyword = $postObj->PicUrl; // 获取图片URL
        $time = time();
        $textTpl = $this->XmlTpl(); // 调用封装的回复XML格式
        if (! empty($keyword)) {
            $msgType = "text";
            $apiurl = file_get_contents('http://api.weiphp.cn/face/?appkey=trialuser&picurl=' . $keyword); // API请求
            $apiurl = str_replace('', "", $apiurl); // 替换函数，详见上同
            $contentStr = $apiurl;
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            echo $resultStr;
        }
    }

    function handleVio($postObj)
    {
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $keyword = $postObj->Recognition; // 语音识别结果
        $time = time();
        $textTpl = $this->XmlTpl();
        if (! empty($keyword)) {
            $msgType = "text";
            $contentStr = $this->ReText($fromUsername, $keyword);
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            echo $resultStr;
        }
    }

    function handleLoc($postObj)
    {
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $lx = $postObj->Location_X;
        $ly = $postObj->Location_Y;
        $wz = $postObj->Label;
        $time = time();
        $textTpl = $this->XmlTpl();
        $msgType = "text";
        $contentStr = "位置精度:" . $lx . " 位置纬度:" . $ly . "地理位置:" . $wz; // 用户发送位置信息结果
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
        echo $resultStr;
    }

    /**
     * 新用户关注回复
     */
    public function handleEvent($object)
    {
        $contentStr = "";
        switch ($object->Event) { // 消息类型
            case "subscribe": // 用户关注事件类型
                $contentStr = "欢迎关注！";
                break; // 继续执行下一片段
            default: // 最终执行，其他类型
                $contentStr = "自定义菜单或其他类型消息";
                break;
        }
        $resultStr = $this->responseText($object, $contentStr);
        return $resultStr;
    }

    public function responseText($object, $content, $flag = 0)
    {
        $textTpl = "<xml>
	                    <ToUserName><![CDATA[%s]]></ToUserName>
	                    <FromUserName><![CDATA[%s]]></FromUserName>
	                    <CreateTime>%s</CreateTime>
	                    <MsgType><![CDATA[text]]></MsgType>
	                    <Content><![CDATA[%s]]></Content>
	                    <FuncFlag>%d</FuncFlag>
	               </xml>"; // 关注事件的XML数据格式
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $flag);
        return $resultStr;
    }

    private function ReText($fromUsername, $keyword)
    { // 关键词处理函数段
        $apiurl = file_get_contents('http://i.itpk.cn/api.php?question=' . $keyword . '&api_key=47016aa198ca53a6e571d2f6a2c57212&api_secret=97cydvok24ve');
        return $apiurl;
    }
 
}  