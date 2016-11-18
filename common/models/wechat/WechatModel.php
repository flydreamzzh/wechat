<?php
namespace common\models\wechat;

use Yii;
use callmez\wechat\sdk\Wechat;
use yii\base\Object;

class WechatModel extends Object
{
    /**
     * @var Wechat $_wechat
     */
    
    
    /**
     * 
     */
    public function valid()
    {
         $_wechat = Yii::$app->wechat;
        $echoStr = $_GET["echostr"];
        if ($_wechat->checkSignature()) {
            echo $echoStr;
            exit();
        }
    }
 
}  