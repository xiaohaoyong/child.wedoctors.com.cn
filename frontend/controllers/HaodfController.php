<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/2/21
 * Time: 下午3:48
 */

namespace frontend\controllers;


use yii\web\Controller;
use EasyWeChat\Factory;

class HaodfController extends Controller
{
    public function actionIndex(){

        $config = [
            // ...
        ];

        $app = Factory::officialAccount(\Yii::$app->params['easywechat']);
        $oauth = $app->oauth;

// 获取 OAuth 授权结果用户信息
        $user = $oauth->user();

        var_dump($user);exit;
        
    }
}