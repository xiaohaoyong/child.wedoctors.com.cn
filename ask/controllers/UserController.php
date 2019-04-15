<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2017/12/6
 * Time: 下午3:08
 */

namespace ask\controllers;

use ask\controllers\Controller;

use common\components\Log;
use common\helpers\HuanxinHelper;
use common\helpers\HuanxinUserHelper;
use common\components\Code;
use common\components\HttpRequest;
use common\components\wx\WxBizDataCrypt;
use common\models\ArticleSend;
use common\models\ChildInfo;
use common\models\DoctorParent;
use common\models\Login;
use common\models\Notice;
use common\models\User;
use common\models\UserDoctor;
use common\models\UserLogin;
use common\models\UserParent;
use common\models\WeOpenid;
use common\models\WxInfo;

class UserController extends Controller
{
    public function actionTest(){
        $userLogin=Login::find()
            ->andWhere(['or',
                ['and',['access_token'=>'123','type'=>4]],
                ['and',['access_token'=>'123','type'=>3]]
            ]);
        echo $userLogin->createCommand()->getRawSql();
    }
    public function actionLogin($code)
    {
//获取用户微信登陆信息
        $path = "/sns/jscode2session?appid=" . \Yii::$app->params['ask_app_id'] . "&secret=" . \Yii::$app->params['ask_app_secret'] . "&js_code=" . $code . "&grant_type=authorization_code";
        $curl = new HttpRequest(\Yii::$app->params['wxUrl'] . $path, true, 10);
        $userJson = $curl->get();
        $user = json_decode($userJson, true);
        if ($user['errcode']) {
            //获取用户微信登陆信息
            $path = "/sns/jscode2session?appid=" . \Yii::$app->params['ask_app_id'] . "&secret=" . \Yii::$app->params['ask_app_secret'] . "&js_code=" . $code . "&grant_type=authorization_code";
            $curl = new HttpRequest(\Yii::$app->params['wxUrl'] . $path, true, 10);
            $userJson = $curl->get();
            $user = json_decode($userJson, true);

            if ($user['errcode']) {
                return new Code(30001, $user['errmsg']);
            }
        }
        return $userJson;

    }
}