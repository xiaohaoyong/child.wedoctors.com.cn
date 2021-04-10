<?php
namespace frontend\controllers;

use common\models\Merge;
use common\models\UserParent;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\ChildInfo;
use common\models\DoctorParent;
use common\models\UserDoctor;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionForm(){
        $merge = new Merge();
        $merge->title="60 岁及以上人群老年人可以接种新冠疫苗么？";
        $merge->content=1;
        $merge->save();
        $merge = new Merge();

        $merge->title="18 岁以下人群青少年可以接种新冠疫苗么？";
        $merge->content=2;
        $merge->save();
        $merge = new Merge();

        $merge->title="慢性病人群可以接种新冠疫苗么？";
        $merge->content=3;
        $merge->save();
        $merge = new Merge();

        $merge->title="育龄期和哺乳期怀孕女性可以接种新冠疫苗么？";
        $merge->content=4;
        $merge->save();
        $merge = new Merge();

        $merge->title="免疫功能受损人群可以接种新冠疫苗么？";
        $merge->content=5;
        $merge->save();
        $merge = new Merge();

        $merge->title="既往新冠患者或感染者可以接种新冠疫苗么？";
        $merge->content=6;
        $merge->save();
        $merge = new Merge();

        $merge->title="糖尿病患者新冠疫苗预防接种指引";
        $merge->content=7;
        $merge->save();
        $merge = new Merge();

        $merge->title="甲状腺疾病患者新冠疫苗预防接种指引？";
        $merge->content=8;
        $merge->save();
        $merge = new Merge();

        $merge->title="高血压患者新冠疫苗预防接种指引？";
        $merge->content="9";
        $merge->save();

    }


    public function actionDown($userid,$type=0)
    {
        $userParent = UserParent::findOne(['userid' => $userid]);
        $doctorParent=DoctorParent::findOne(['parentid'=>$userid]);
        $userDoctor=UserDoctor::findOne(['userid'=>$doctorParent->doctorid]);
        $child=ChildInfo::find()->select('name')->where(['userid'=>$userid])->column();


        return $this->renderPartial('down',[
            'userParent'=>$userParent,
            'userid'=>$userid,
            'userDoctor'=>$userDoctor,
            'child'=>$child,
        ]);
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
