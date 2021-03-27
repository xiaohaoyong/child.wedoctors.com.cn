<?php
namespace doctor\controllers;

use common\components\Code;
use common\helpers\SmsSend;
use common\models\Article;
use common\models\ArticleUser;
use common\models\Autograph;
use common\models\ChildInfo;
use common\models\DoctorHospital;
use common\models\DoctorParent;
use common\models\Hospital;
use common\models\Pregnancy;
use common\models\UserDoctor;
use Yii;
use common\models\LoginForm;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends BaseController
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'height' => 50,
                'width' => 100,
                'maxLength' => 4,
                'minLength' => 4
            ],
        ];
    }
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['logout','index','login', 'error', 'captcha','code','hospitals'],
                        'allow' => true,
                    ],
                ],
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
        return $this->render('index');
    }


    public function actionHospitals($hospitalid=0){

        return $this->render('hospitals',[
            'hospitals'=>$hospitals
        ]);
    }
    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {     //①
            return $this->goHome();
        }

        $model = new \doctor\models\LoginForm();             //②
        if ($model->load(Yii::$app->request->post()) && $model->login()) {      //③
            return $this->redirect(['site/index']);
            //return $this->goBack();          //④
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    /**
     * 发送验证码
     * @param $phone
     * @return Code
     */
    public function actionCode($phone){
        \Yii::$app->response->format=Response::FORMAT_JSON;

        if(!preg_match("/^1[34578]\d{9}$/", $phone)){
            return ['code'=>20010,'msg'=>'手机号码格式错误'];
        }
        $sendData=SmsSend::sendSms($phone,'SMS_150575871');
        return ['code'=>10000,'msg'=>'成功'];

    }
}
