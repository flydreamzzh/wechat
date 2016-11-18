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
    public $_wechat = Yii::$app->wechat;
    
    /**
     * 
     */
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if ($this->_wechat->checkSignature()) {
            echo $echoStr;
            exit();
        }
    }
 
}  