<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/4/22
 * Time: 上午11:40
 */

namespace frontend\controllers;
use common\models\Log;
use common\models\User;
use common\models\UserLogin;
use EasyWeChat\Factory;


class Controller extends \yii\web\Controller
{
    public $login;
    private $ignore = [
        'oauth-callback/index',
        'wappoint/day-num',
        'wappoint/code',
        'wappoint/vphone',
        'wappoint/test'
    ];

    public function beforeAction($action)
    {
        session_start();

        $this->layout = "@frontend/views/layouts/h5.php";
        $path = \Yii::$app->request->pathInfo;
        parent::beforeAction($action);

        if (in_array($path, $this->ignore))
        {
            return true;
        }
        $this->login=UserLogin::findOne(['userid'=>1292985]);
        $config=\Yii::$app->params['wechat'];
        $config['oauth']['callback']=\Yii::$app->request->url;
        $app = Factory::officialAccount($config);
        $oauth = $app->oauth;
        if(\Yii::$app->request->get('code') && !$_SESSION['wechat_user'] && $path!='wappoint/index') {
            $wechat_user=$oauth->user();
            if($wechat_user){
                $_SESSION['wechat_user']=$wechat_user;
            }
        }
// 未登录
        if (!$_SESSION['wechat_user'] && !\Yii::$app->request->get('code')) {

            $oauth->redirect()->send();
            // 这里不一定是return，如果你的框架action不是返回内容的话你就得使用
            // $oauth->redirect()->send();
        }
        $wechat_user=$_SESSION['wechat_user'];
        $openid=$wechat_user['original']['openid'];
        $unionid=$wechat_user['original']['unionid'];
        $query=UserLogin::find()->where(['openid' => $openid]);
//        if($unionid){
//            $query->orWhere(['and', ['unionid' => $unionid]]);
//        }
        $query->andWhere(['type'=>0]);
        $login=$query->one();
        if($login){
            $this->login=$login;
        }elseif($openid){
            $user=new User();
            $user->type=0;
            $user->level=1;
            $user->phone=0;
            $user->source=3;
            if($user->save()){
                $login=new UserLogin();
                $login->userid=$user->id;
                $login->phone=0;
                $login->openid=$openid;
                $login->unionid=$unionid;
                $login->type=0;
                $login->save();
                $this->login=$login;
            }else{
                return false;
            }
        }
        return true;
    }
}