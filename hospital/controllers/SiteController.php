<?php
namespace hospital\controllers;

use app\models\Ausers;
use common\components\Code;
use common\helpers\SmsSend;
use common\models\Appoint;
use common\models\AppointComment;
use common\models\Article;
use common\models\ArticleUser;
use common\models\Auser;
use common\models\Autograph;
use common\models\ChildInfo;
use common\models\DoctorHospital;
use common\models\DoctorParent;
use common\models\Hospital;
use common\models\Pregnancy;
use common\models\Question;
use common\models\QuestionComment;
use common\models\QuestionReply;
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
        $doctorid = UserDoctor::findOne(['hospitalid' => \Yii::$app->user->identity->hospitalid])->userid;

        $today=strtotime(date('Y-m-d 00:00:00'));

        //今日签约数
        $data['todayNum']=ChildInfo::find()
            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
            ->andFilterWhere(['`doctor_parent`.`level`' => 1])
            ->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid])
            ->andFilterWhere(['child_info.admin'=>\Yii::$app->user->identity->hospitalid])
            ->andFilterWhere([">",'`doctor_parent`.createtime',$today])
            ->andFilterWhere(['>','child_info.birthday',strtotime('-3 year')])
            ->count();

        //今日签字数
        $data['todayInkNum']=Autograph::find()
            ->andFilterWhere(['doctorid'=>$doctorid])
            ->andFilterWhere([">",'createtime',$today])
            ->count();

        //签约儿童总数
        $data['todayNumTotal']=ChildInfo::find()
            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
            ->andFilterWhere(['`doctor_parent`.`level`' => 1])
            ->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid])
            ->andFilterWhere(['>', '`child_info`.birthday', strtotime('-3 year')])
            ->andFilterWhere(['child_info.admin'=>\Yii::$app->user->identity->hospitalid])
            ->count();


        //管辖儿童数（0-3）
        $data['childNum']=ChildInfo::find()
            ->andFilterWhere(['>','child_info.birthday',strtotime('-3 year')])
            ->andFilterWhere(['`child_info`.`source`' => \Yii::$app->user->identity->hospitalid])
            ->andFilterWhere(['`child_info`.admin'=>\Yii::$app->user->identity->hospitalid])
            ->count();

        //管辖儿童数（0-6）
        $adminsix=ChildInfo::find()
            ->andFilterWhere(['>', '`child_info`.birthday', strtotime('-6 year')])
            ->andFilterWhere(['`child_info`.admin'=>\Yii::$app->user->identity->hospitalid])
            ->count();

        $nadminsix=ChildInfo::find()
            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
            ->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid])
            ->andFilterWhere(['`doctor_parent`.`level`' => 1])
            ->andWhere(['!=','`child_info`.`admin`' ,\Yii::$app->user->identity->hospitalid])
            ->andFilterWhere(['>', '`child_info`.birthday', strtotime('-6 year')])
            ->count();

        $data['achildNum']=$adminsix+$nadminsix;
        //var_dump(\Yii::$app->user->identity->hospitalid);exit;
        //签约率
        if($data['childNum']) {
            $data['baifen'] = round(($data['todayNumTotal'] / $data['childNum']) * 100,1);
        }else{
            $data['baifen'] = 0;
        }




        $auto=Autograph::find()->select('userid')->where(['doctorid'=>$doctorid])->column();
        if($auto) {
            $data['AutoNum'] = ChildInfo::find()
                ->andFilterWhere(['in', '`child_info`.`userid`', array_unique($auto)])
                ->andFilterWhere(['>', '`child_info`.birthday', strtotime('-6 year')])
                ->count();
        }else{
            $data['AutoNum']=0;
        }

        //签约率
        if($data['AutoNum'] && $data['achildNum']) {
            $data['abaifen'] = round(($data['AutoNum'] / $data['achildNum']) * 100,1);
        }else{
            $data['abaifen'] = 0;
        }

        //宣教总次数
        $data['articleNum']=ArticleUser::find()
            ->andFilterWhere(['article_user.userid'=>$doctorid])
            ->count();

        $data['articleNumToday']=ArticleUser::find()
            ->andFilterWhere(['>','createtime',$today])
            ->andFilterWhere(['article_user.userid'=>$doctorid])
            ->count('distinct childid');


        $doctorParent= DoctorParent::find()->select('parentid')->where(['doctorid'=>$doctorid])->andFilterWhere(['level'=>1])->column();

        //该类型 本月已发送的儿童
        $articleUser=ArticleUser::find()->select('touserid')
            ->where(['userid'=>$doctorid])
            //->andFilterWhere(['>','createtime',strtotime($lmount)])
            ->groupBy('childid')
            ->column();

        $users=array_diff($doctorParent,$articleUser);

        if($users) {
            foreach (Article::$childText as $k => $v) {
                if ($k) {
                    $mouth[] = ChildInfo::getChildTypeDay($k);
                }
            }
            $childCount = ChildInfo::find()->andFilterWhere(['in', 'birthday', $mouth])->andFilterWhere(['in', 'userid', array_values($users)])->count();
        }else{
            $childCount=0;
        }
        $data['articleNoMonth']=$childCount;

        for($i=9;$i>-1;$i--)
        {
            $time=strtotime("-$i day");
            $date=date('Y-m-d',$time);
            $st=strtotime($date."00:00:00");
            $end=$st+3600*24;
            $rs['item1']=ChildInfo::find()
                ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
                ->andFilterWhere(['`doctor_parent`.`level`' => 1])
                ->andFilterWhere(['>','`doctor_parent`.createtime',$st])
                ->andFilterWhere(['<','`doctor_parent`.createtime',$end])
                ->andFilterWhere(['doctor_parent.doctorid'=>$doctorid])
                ->count();

            $rs['day']=$date;
            $line_data[]=$rs;
        }

        $now=ChildInfo::find()
            ->leftJoin('user','`user`.`id`=`child_info`.`userid`')
            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
            ->andFilterWhere(['`doctor_parent`.`level`'=>1])
            ->andFilterWhere(['doctor_parent.doctorid'=>$doctorid])
            ->andFilterWhere(['child_info.admin'=>\Yii::$app->user->identity->hospitalid])
            ->orderBy('`doctor_parent`.`createtime` desc')->limit(9)->all();



        $data['pregCount']=Pregnancy::find()
            ->andWhere(['field49'=>0])->andWhere(['>','field11',strtotime('-43 week')])->andWhere(['doctorid'=>\Yii::$app->user->identity->hospitalid])->count();

        $data['pregLCount']=Pregnancy::find()
            ->andWhere(['pregnancy.field49'=>0])
            ->andWhere(['>','pregnancy.field11',strtotime('-43 week')])
            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `pregnancy`.`familyid`')
            ->andWhere(['pregnancy.doctorid'=>\Yii::$app->user->identity->hospitalid])
            ->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid])->count();

        $data['todayPregLCount']=Pregnancy::find()
            ->andWhere(['pregnancy.field49'=>0])
            ->andWhere(['>', 'pregnancy.familyid', 0])
            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `pregnancy`.`familyid`')
            ->andWhere(['>','doctor_parent.createtime',strtotime(date('Y-m-d'))])
            ->andWhere(['<','doctor_parent.createtime',strtotime(date('Y-m-d 23:59:59'))])

            ->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid])->count();




        ##############################
        ###就诊评价统计
        $visit_stat['visit_total'] = Appoint::find()->where(['state'=>2,'doctorid'=>$doctorid])->count() ;    #总就诊完成数
        $visit_stat['comment_total'] = AppointComment::find()->where(['doctorid'=>$doctorid])->count() ;      #就诊评价数
        $visit_stat['comment_total_hp'] = AppointComment::find()->where(['is_rate'=>1,'doctorid'=>$doctorid])->count() ;    #就诊好评数量
        $visit_stat['comment_total_zp'] = AppointComment::find()->where(['is_rate'=>2,'doctorid'=>$doctorid])->count() ;    #就诊中评数量
        $visit_stat['comment_total_cp'] = AppointComment::find()->where(['is_rate'=>3,'doctorid'=>$doctorid])->count() ;    #就诊差评数量


        ##############################
        ###问题回复统计
        #问题总数
        $question_stat['question_total'] = Question::find()->where(['doctorid'=>$doctorid])->count() ;

        $dbi = (new Question())->find();

        #回复总数
        $dbi->sql = 'select count(DISTINCT(qid)) as num from question tb1,question_reply tb2 where tb1.id = tb2.qid and tb1.doctorid='.$doctorid;
        $question_stat['reply_total'] = $dbi->createCommand()->queryOne()['num'];

        #巡医团队回复总数
        $dbi->sql = 'select count(DISTINCT(qid)) as num from question tb1,question_reply tb2 where tb1.id = tb2.qid and tb1.doctorid='.$doctorid.' and  tb2.userid=47156';
        $question_stat['reply_total_xyitem'] = $dbi->createCommand()->queryOne()['num'];

        #社区回复总数
        $dbi->sql = 'select count(DISTINCT(qid)) as num from question tb1,question_reply tb2 where tb1.id = tb2.qid and tb1.doctorid='.$doctorid.' and  tb2.userid='.$doctorid;
        $question_stat['reply_total_item'] = $dbi->createCommand()->queryOne()['num'];

        #总回复率
        $question_stat['question_total_reply_rate'] =  $question_stat['question_total'] ? round($question_stat['reply_total'] / $question_stat['question_total'])*100 : 0;

        #巡医团队回复占比
        $question_stat['reply_total_xyitem_percent'] = $question_stat['reply_total'] ? round($question_stat['reply_total_xyitem'] /  $question_stat['reply_total'])*100 : 0;

        #社区医院回复占比
        $question_stat['reply_total_item_percent'] = $question_stat['reply_total'] ? round($question_stat['reply_total_item'] /  $question_stat['reply_total'])*100 : 0;



        #总评价数量
        $question_stat['comment_total'] = QuestionComment::find()->where(['doctorid'=>$doctorid])->count() ;

        #满意的评价数量
        $question_stat['comment_total_satisfied'] = QuestionComment::find()->where(['is_satisfied'=>2,'doctorid'=>$doctorid])->count() ;

        #解决问题的评价数量
        $question_stat['comment_total_solve'] = QuestionComment::find()->where(['is_solve'=>2,'doctorid'=>$doctorid])->count() ;

        #满意度
        $question_stat['comment_satisfied_rate'] = $question_stat['comment_total']  ? round($question_stat['comment_total_satisfied'] / $question_stat['comment_total'])*100 : 0;

        #问题解决率
        $question_stat['comment_solve_rate'] = $question_stat['omment_total']  ? round($question_stat['comment_total_solve'] / $question_stat['comment_total'])*100 : 0;



        return $this->render('index',[
            'data'=>$data,
            'line_data'=>$line_data,
            'now'=>$now,
            'visit_stat'=>$visit_stat,
            'question_stat'=>$question_stat,
        ]);
    }


    public function actionHospitals($hospitalid=0,$redirect=''){
        $this->layout = "@hospital/views/layouts/main-login.php";

        if($hospitalid){

            $cookies = Yii::$app->response->cookies;
            $cookies->add(new \yii\web\Cookie([
                'name' => 'hospital',
                'value' => $hospitalid,
                'expire'=>time()+3600000
            ]));
            if($redirect){
                return $this->redirect([$_GET['redirect']]);

            }
            return $this->redirect(['site/index']);

        }

        $hospitalids=DoctorHospital::find()->select('hospitalid')->where(['doctorid'=>\Yii::$app->user->identity->userid])->column();
        $hospitals=Hospital::find()->select('name')->indexBy('id')->where(['id'=>$hospitalids])->column();

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

        $model = new \hospital\models\LoginForm();             //②
        if ($model->load(Yii::$app->request->post()) && $model->login()) {      //③
            return $this->redirect(['site/hospitals','redirect'=>$_GET['redirect']]);
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
