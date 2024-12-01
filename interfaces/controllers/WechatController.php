<?php

namespace api\controllers;

use callmez\wechat\sdk\MpWechat;
use yii\web\Controller;
use yii\web\Response;

class WechatController extends Controller
{
    public function actionGetAccessToken()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $mpWechat = new MpWechat(['token' => \Yii::$app->params['WeToken'], 'appId' => \Yii::$app->params['AppID'], 'appSecret' => \Yii::$app->params['AppSecret'], 'encodingAesKey' => \Yii::$app->params['encodingAesKey']]);
        return $mpWechat->getAccessToken();
    }
}