<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\models\wechat\WechatHelper;
use common\models\wechat\WechatModel;

/**
 * Entrance controller
 */
class EntranceController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $wechat = new WechatModel();
        if(!isset($_GET["echostr"])) {
            $wechat->responseMsg();
        }else {
            $wechat->valid();
        }
    }
    
//    public function actionResponse()
//    {
//        $wechat = new WechatHelper();
//        $wechat->responseMsg();
//    }
    
}
