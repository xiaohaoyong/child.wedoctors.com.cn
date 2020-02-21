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



        $app = Factory::officialAccount(\Yii::$app->params['easywechat']);
        $oauth = $app->oauth;
        if(\Yii::$app->request->get('code')) {
// 获取 OAuth 授权结果用户信息
            $user = $oauth->user();

        }
        var_dump($user);exit;

        echo "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1147c2e491dfdf1d&redirect_uri=http://child.wedoctors.com.cn/haodf&response_type=code&scope=SCOPE&state=STATE#wechat_redirec";
    }
}