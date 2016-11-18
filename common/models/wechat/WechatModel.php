<?php
namespace common\models\wechat;

use Yii;
use callmez\wechat\sdk\Wechat;
use yii\base\Object;

class WechatModel extends Object
{
    /**
     * @return Wechat $_wechat
     */
    public function wechat()
    {
        $_wechat = Yii::$app->wechat;
        return $_wechat;
    }
    
    /**
     * 
     */
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if ($this->wechat()->checkSignature()) {
            echo $echoStr;
            exit();
        }
    }
 
}  