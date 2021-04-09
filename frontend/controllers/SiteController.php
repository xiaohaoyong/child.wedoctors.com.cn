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
        $merge->title="本次使用的是哪种新冠疫苗？";
        $merge->content=1;
        $merge->save();
        $merge = new Merge();

        $merge->title="新冠疫苗打几针？间隔时间？";
        $merge->content=2;
        $merge->save();
        $merge = new Merge();

        $merge->title="新冠疫苗有必要接种吗？";
        $merge->content=3;
        $merge->save();
        $merge = new Merge();

        $merge->title="新冠疫苗的安全性和有效性如何？";
        $merge->content=4;
        $merge->save();
        $merge = new Merge();

        $merge->title="接种新冠疫苗怎么预约？";
        $merge->content=5;
        $merge->save();
        $merge = new Merge();

        $merge->title="接种新冠疫苗可能会出现什么不良反应？出现不良反应怎么办？";
        $merge->content=6;
        $merge->save();
        $merge = new Merge();

        $merge->title="60岁以上和18岁以下人群可以接种新冠疫苗吗？";
        $merge->content=7;
        $merge->save();
        $merge = new Merge();

        $merge->title="哪些人不适宜接种新冠疫苗？";
        $merge->content=8;
        $merge->save();
        $merge = new Merge();

        $merge->title="哪些人不适宜打第二剂次疫苗？";
        $merge->content="9";
        $merge->save();
        $merge = new Merge();

        $merge->title="接种新冠疫苗前需要做哪些准备？";
        $merge->content=10;
        $merge->save();
        $merge = new Merge();

        $merge->title="接种新冠疫苗时应注意什么？";
        $merge->content=11;
        $merge->save();
        $merge = new Merge();

        $merge->title="接种新冠疫苗后还要做什么？";
        $merge->content=12;
        $merge->save();
        $merge = new Merge();

        $merge->title="接种完新冠疫苗是否可以喝酒、吃辛辣食物？";
        $merge->content=13;
        $merge->save();
        $merge = new Merge();

        $merge->title="接种完新冠疫苗是否可以洗澡？";
        $merge->content=14;
        $merge->save();
        $merge = new Merge();

        $merge->title="接种新冠疫苗后可以放松个人防护措施吗？";
        $merge->content=15;
        $merge->save();
        $merge = new Merge();

        $merge->title="慢性病患者可以接种新冠疫苗吗？（常年吃药患有高血压的人、糖尿病、甲状腺、心脏病等）";
        $merge->content=16;
        $merge->save();
        $merge = new Merge();

        $merge->title="有过敏史的可以接种新冠疫苗吗？";
        $merge->content=17;
        $merge->save();
        $merge = new Merge();

        $merge->title="备孕期可以打新冠疫苗吗？接种后多久能要孩子？";
        $merge->content=18;
        $merge->save();
        $merge = new Merge();

        $merge->title="新冠肺炎治愈人群需要接种新冠疫苗吗？";
        $merge->content=19;
        $merge->save();
        $merge = new Merge();

        $merge->title="外籍人士可以在北京打新冠疫苗吗？";
        $merge->content=20;
        $merge->save();
        $merge = new Merge();

        $merge->title="接种新冠疫苗花钱吗？";
        $merge->content=21;
        $merge->save();


        $query = $merge::find()->all();  //这句话是查询所有的数据（但是es本身有限制，所以只会查出来10条，下面我会说怎么查更多数据）
        var_dump($query);

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
