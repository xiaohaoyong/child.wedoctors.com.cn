<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/4/22
 * Time: 上午11:24
 */

namespace frontend\controllers;
use EasyWeChat\Factory;


class OauthCallbackController
{
    public function actionIndex(){

        $app = Factory::officialAccount(\Yii::$app->params['wechat']);
        $oauth = $app->oauth;

// 获取 OAuth 授权结果用户信息
        $user = $oauth->user();

        $_SESSION['wechat_user'] = $user->toArray();

        $targetUrl = empty($_SESSION['target_url']) ? '/' : $_SESSION['target_url'];

        header('location:'. $targetUrl); // 跳转到 user/profile
    }

}