<?php
namespace hospital\controllers;

use common\models\Article;
use common\models\ArticleUser;
use common\models\ChildInfo;
use common\models\DoctorParent;
use common\models\UserDoctor;
use Yii;
use common\models\LoginForm;
use yii\filters\AccessControl;

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
                        'actions' => ['logout','index','login', 'error', 'captcha'],
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

        $doctorid = UserDoctor::findOne(['hospitalid' => \Yii::$app->user->identity->hospital])->userid;

        //今日签约数
        $today=strtotime(date('Y-m-d 00:00:00'));
        $month=strtotime(date('Y-m-01 00:00:00'));



        //今日签约数
        $data['todayNum']=ChildInfo::find()
            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
            ->andFilterWhere(['`doctor_parent`.`level`' => 1])
            ->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid])
            ->andFilterWhere([">",'`doctor_parent`.createtime',$today])
            ->count();

        //签约儿童总数
        $data['todayNumTotal']=ChildInfo::find()
            ->leftJoin('doctor_parent', '`doctor_parent`.`parentid` = `child_info`.`userid`')
            ->andFilterWhere(['`doctor_parent`.`level`' => 1])
            ->andFilterWhere(['`doctor_parent`.`doctorid`' => $doctorid])
            ->andFilterWhere(['`child_info`.`doctorid`' => \Yii::$app->user->identity->hospital])
            ->count();

        //var_dump($data['todayNumTotal']->createCommand()->getRawSql());exit;


        //管辖儿童数
        $data['childNum']=ChildInfo::find()
            ->andFilterWhere(['>','child_info.birthday',strtotime('-3 year')])
            ->andFilterWhere(['`child_info`.`source`' => \Yii::$app->user->identity->hospital])
            ->andFilterWhere(['`child_info`.admin'=>\Yii::$app->user->identity->hospital])
            ->count();

        //签约率
        if($data['childNum']) {
            $data['baifen'] = round(($data['todayNumTotal'] / $data['childNum']) * 100,1);
        }else{
            $data['baifen'] = 0;
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
            ->orderBy('`doctor_parent`.`createtime` desc')->limit(9)->all();


        return $this->render('index',[
            'data'=>$data,
            'line_data'=>$line_data,
            'now'=>$now,
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
            return $this->goBack();          //④
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
}
