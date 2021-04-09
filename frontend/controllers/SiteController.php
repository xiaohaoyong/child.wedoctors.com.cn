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
        $merge->content="北京市目前使用的是全病毒灭活疫苗，通过一定方法使新冠病毒失去感染性和复制力，同时保留能引起人体免疫应答活性而制备成的疫苗。灭活疫苗是传统经典的疫苗制备方式，属于成熟、可靠、经典的疫苗制备手段，相对于其它疫苗研制技术而言，灭活疫苗研发平台成熟、生产工艺稳定、质量标准可控、保护效果良好、研发速度快，且易于规模化生产，具有国际通行的安全性和有效性评判标准。";
        $merge->save();

        $merge->title="新冠疫苗打几针？间隔时间？";
        $merge->content="新冠病毒灭活疫苗要打2针，两针间隔21天到28天。如特殊原因过了28天还没打上，一定尽快完成第二针，这样才能得到完整的全程免疫保护效果。";
        $merge->save();

        $merge->title="新冠疫苗有必要接种吗？";
        $merge->content="非常有必要！
新冠肺炎是由新冠病毒感染引起、以呼吸道飞沫和密切接触为主要传播途径的新发传染病。几乎所有人都没有新冠病毒的免疫力，几乎所有人对新冠病毒都易感。
安全有效的疫苗是预防疾病最有力的武器，接种疫苗后可以刺激人体产生抵抗新冠病毒的免疫力，阻挡新冠病毒感染，特别是有效降低重症和死亡率。目前，疫苗说明书所适用的人群，在没有接种禁忌症的情况下，都应该接种新冠疫苗";
        $merge->save();

        $merge->title="新冠疫苗的安全性和有效性如何？";
        $merge->content="我国疫苗在研发、试验、审批、上市、储存、运输、接种等全流程都有非常严格的管理规定。有完善的疫苗冷链系统保障，储存和运输均严格按照规范执行。接种单位、医护人员都经过了专业培训和严格审核，按照标准操作程序进行接种。
前期试验表明，全程接种28天后90%以上受种者都会产生保护抗体，保护效果明显。少数人接种后接种部位有红肿、硬结、疼痛，极少数人出现发热、乏力、恶心、头痛、肌肉酸痛等症状，通常无需处理，一般1-2天可自行恢复。目前全国接种新冠疫苗超过5000万剂次，更进一步证明我国疫苗是安全的。";
        $merge->save();

        $merge->title="接种新冠疫苗怎么预约？";
        $merge->content="有疫苗接种需求的人员可通过所在单位或所在社区进行预约，具体情况请关注各区的有关通知。";
        $merge->save();

        $merge->title="接种新冠疫苗可能会出现什么不良反应？出现不良反应怎么办？";
        $merge->content="常见的不良反应主要包括以下几方面：接种部位局部疼痛、红晕或者出现了硬块，全身乏力、发热、头痛，另外还有一些人有咳嗽、食欲不振、呕吐、腹泻等常见的不良反应。
接种对象完成接种后，应在接种现场留观30分钟方可离开，现场工作人员会告知受种者接种新冠疫苗常见不良反应、注意事项、后续健康状况观察、处置建议以及联系方式等。回家后如出现不良反应相关症状，报告接种点工作人员，必要时及时就医。";
        $merge->save();

        $merge->title="60岁以上和18岁以下人群可以接种新冠疫苗吗？";
        $merge->content="有接种需求且身体基础状况较好的60岁及以上老年人接种新冠疫苗。根据疫苗研发进展和后续临床试验结果，按照国家统一要求安排全市18岁以下人群接种工作。";
        $merge->save();

        $merge->title="哪些人不适宜接种新冠疫苗？";
        $merge->content="对疫苗或疫苗成分过敏，有过疫苗接种严重过敏反应的；有未控制的严重慢性疾病、未控制的癫痫、脑病和其他进行性神经系统疾病者不适宜接种；有严重慢性疾病的，过敏体质，精神疾病史或家族史，有血小板减少症或者出血性疾病的，免疫功能受损（例如恶性肿瘤、肾病综合征、艾滋病患者）的人群慎用；怀孕的、哺乳期女性、各种疾病急性发作时要暂缓接种。
接种时，要认真阅读知情同意书，主动告知医务人员你的健康情况，医生会帮助判断是否可以接种。";
        $merge->save();

        $merge->title="哪些人不适宜打第二剂次疫苗？";
        $merge->content="受种对象身体状况应符合新冠病毒疫苗接种条件。如果接种第一剂疫苗出现严重过敏反应，或出现任何神经系统不良反应者，且不能排除是疫苗引起的，则不建议接种第二剂次；另外受种对象接种第二剂次时患急性疾病、慢性疾病的急性发作期应暂缓接种。";
        $merge->save();

        $merge->title="接种新冠疫苗前需要做哪些准备？";
        $merge->content="需要了解新冠疫苗接种相关知识；解接种点的接种流程；确定是否需要提前预约；按要求带好身份证；接种当天穿宽松的衣服方便接种；出门时还得戴好口罩。";
        $merge->save();

        $merge->title="接种新冠疫苗时应注意什么？";
        $merge->content="接种时，要主动告知医务人员你的健康情况，医生会帮助判断是否可以接种；认真阅读知情同意书并签字，按照要求进行接种，并确认第二针接种时间。切记要全程戴好口罩，按接种点标识有序排队，保持好社交距离。";
        $merge->save();

        $merge->title="接种新冠疫苗后还要做什么？";
        $merge->content="接种之后，要像小孩打预防针一样留观30分钟，没有异常情况才可以离开。回家后如出现发烧不退或持续不舒服，要向接种点报告并及时就医。";
        $merge->save();

        $merge->title="接种完新冠疫苗是否可以喝酒、吃辛辣食物？";
        $merge->content="正常的活动、饮食不影响疫苗效果，也无需改变原有的生活习惯。但接种疫苗后出现发热、乏力、甚至恶心、腹泻等症状时，应适当调整饮食、注意休息，避免过度劳累，更不要酗酒或暴饮暴食。";
        $merge->save();

        $merge->title="接种完新冠疫苗是否可以洗澡？";
        $merge->content="接种新冠疫苗后（包括其它疫苗）可以洗澡。接种人员往往在接种后嘱咐受种者当天不要洗澡，其目的主要是为了防止注射部位的感染，避免或减轻局部反应的发生。新冠疫苗含有吸附制剂，部分受种者在接种疫苗后，接种部位可能会出现红肿或硬结，洗澡时要避免过度按压、刺激，洗澡后要保持局部清洁。";
        $merge->save();

        $merge->title="接种新冠疫苗后可以放松个人防护措施吗？";
        $merge->content="接种疫苗后虽然可以产生免疫力，可以大大降低感染风险，但任何疫苗保护作用不可能100%，部分人接种后有可能不产生足够抗体，仍然会有感染风险，特别是在还没有建立起社会防疫屏障的情况下。所以，即使打完疫苗，也要保持戴口罩、勤洗手、保持社交距离等良好卫生习惯。";
        $merge->save();

        $merge->title="慢性病患者可以接种新冠疫苗吗？（常年吃药患有高血压的人、糖尿病、甲状腺、心脏病等）";
        $merge->content="新冠灭活疫苗说明书中提示：慢性疾病的急性发作期、严重慢性疾病患者慎用。
1）患有高血压、糖尿病等慢性疾病患者，通过生活方式调整和（或）药物治疗，血压可维持在相对正常水平，没有糖尿病并发症，原则上可以接种新冠疫苗，具体请按临床医师医嘱执行。
2）甲状腺功能减退、甲状腺功能亢进患者服用稳定剂量药物，病情平稳的，原则上可以进行接种。甲状腺功能减退（TSH＞10μIU/mL，且T3、T4低于正常值）的患者以及未控制的甲状腺功能亢进或甲亢性突眼患者，建议暂缓接种。
3）心脏病等心血管系统疾病如果处于急性发作期应暂缓接种疫苗。";
        $merge->save();

        $merge->title="有过敏史的可以接种新冠疫苗吗？";
        $merge->content="新冠疫苗说明书中的禁忌包括：既往发生过疫苗接种严重过敏反应（如急性过敏反应、血管神经性水肿、呼吸困难等）不得接种。因此要考虑过敏体质的严重程度，并按照临床医师的医嘱执行。";
        $merge->save();

        $merge->title="备孕期可以打新冠疫苗吗？接种后多久能要孩子？";
        $merge->content="新冠疫苗说明书中的禁忌要求孕妇、哺乳期妇女不得接种。因此备孕妇女须在疫苗接种后适当推迟怀孕时间，参考其他灭活疫苗，建议在接种3个月以后怀孕。";
        $merge->save();

        $merge->title="新冠肺炎治愈人群需要接种新冠疫苗吗？";
        $merge->content="一般来说，感染过传染病或无症状感染，均会产生相应的免疫力，应该具有类似接种疫苗的保护作用。并且，目前临床试验尚未提供新冠肺炎治愈人群的安全性和有效性数据。因此，新冠肺炎治愈人群不推荐接种新冠疫苗。";
        $merge->save();

        $merge->title="外籍人士可以在北京打新冠疫苗吗？";
        $merge->content="外籍人士暂时没有开放接种，我们将根据国家具体政策开启外籍人士接种。";
        $merge->save();

        $merge->title="接种新冠疫苗花钱吗？";
        $merge->content="按照目前国家政策，新冠疫苗实施免费接种，居民个人不负担费用。";
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
