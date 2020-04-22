<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/4/22
 * Time: 上午11:40
 */

namespace frontend\controllers;
use EasyWeChat\Factory;


class Controller extends \yii\web\Controller
{
    public $user;
    private $ignore = [
        'oauth-callback/index',
        'wappoint/day-num',
        'wappoint/code',
        'wappoint/vphone',
    ];

    public function beforeAction($action)
    {
        $this->layout = "@frontend/views/layouts/h5.php";
        $path = \Yii::$app->request->pathInfo;
        parent::beforeAction($action);

        if (in_array($path, $this->ignore))
        {
            return true;
        }
        $app = Factory::officialAccount(\Yii::$app->params['wechat']);
        $oauth = $app->oauth;

// 未登录
        if (empty($_SESSION['wechat_user'])) {

            $_SESSION['target_url'] = \Yii::$app->request->url;

            return $oauth->redirect();
            // 这里不一定是return，如果你的框架action不是返回内容的话你就得使用
            // $oauth->redirect()->send();
        }
        $this->user=$_SESSION['wechat_user'];
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }
}